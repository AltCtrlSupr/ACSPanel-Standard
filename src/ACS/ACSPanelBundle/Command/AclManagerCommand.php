<?php

namespace ACS\ACSPanelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class AclManagerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acl-manager:update-entity')
            ->setDescription('Update ACL entries based on current entity permissions')
            ->addArgument(
                'entity',
                InputArgument::OPTIONAL,
                'Entity name to update based permissions'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entity = $input->getArgument('entity');
        $aclManager = $this->getContainer()->get('problematic.acl_manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Adding master permissions to superadmins
        $superadmins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getSuperadminUsers();
        $admins = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->getAdminUsers();

        $entities = $em->getRepository($entity)->findAll();

        foreach ($entities as $entity) {

            $user = $entity->getOwners();
            // If we get a single user we add him to object owner
            if (is_object($user)) {
                $output->writeln($this->addUserOwnerPermission($user, $entity));
            }

            // If we receive an array we iterate it
            if (is_array($user)) {
                foreach ($user as $owner) {
                    $output->writeln($this->addUserOwnerPermission($owner, $entity));
                }
            }

            if ($user == 'admins') {
                foreach ($admins as $admin) {
                    $output->writeln($this->addUserOwnerPermission($admin, $entity));
                }
            }

            foreach ($superadmins as $superadmin) {
                $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $superadmin);
                $output->writeln("Added ". $entity ." (" . get_class($entity) . ") Acls for " . $superadmin);
            }
        }
    }

    public function addUserOwnerPermission($user, $entity)
    {
        $aclManager = $this->getContainer()->get('problematic.acl_manager');

        $parent = '';

        if ($parent = $user->getParentUser()) {
            $aclManager->addObjectPermission($entity, MaskBuilder::MASK_MASTER, $parent);
            $parent = ' and ' . $parent;
        }

        $aclManager->addObjectPermission($entity, MaskBuilder::MASK_OWNER, $user);

        return "Added ". $entity ." (" . get_class($entity) . ") Acls for " . $user . $parent;

    }
}
