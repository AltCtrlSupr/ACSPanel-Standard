<?php

namespace ACS\ACSPanelSettingsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use ACS\ACSPanelBundle\Entity\User;

class ServiceToStringTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (user) to a string (number).
     *
     * @param  user|null $user
     * @return string
     */
    public function transform($user)
    {
      return $user;
    }

    /**
     * Transforms a string (number) to an object (user).
     *
     * @param  string $number
     * @return user|null
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($service_id)
    {
        if (!$service_id) {
            return null;
        }

        $service = $this->om
            ->getRepository('ACSACSPanelBundle:Service')
            ->findOneBy(array('id' => $service_id))
        ;

        if (null === $service) {
            throw new TransformationFailedException(sprintf(
                'A service with id "%s" does not exist!',
                $service_id
            ));
        }

        return $service;
    }
}
