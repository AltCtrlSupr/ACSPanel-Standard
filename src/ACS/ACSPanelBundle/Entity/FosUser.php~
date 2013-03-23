<?php


namespace ACS\ACSPanelBundle\Entity;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class FosUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $groups;
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $child_users;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $plans;

    /**
     * @var \ACS\ACSPanelBundle\Entity\FosUser
     */
    private $parent_user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $user_plan;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $puser;
    /**
     * @var integer
     */
    private $uid;

    /**
     * @var integer
     */
    private $gid;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $services;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $httpdhosts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $maildomains;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $databases;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $settings;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getExpiresAt(){
        return $this->expiresAt;
    }

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    public function getRoles()
    {
        return parent::getRoles();
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return parent::getGroups();
    }

    /**
     * Add groups
     *
     * @param \ACS\ACSPanelBundle\Entity\FosGroup $groups
     * @return FosUser
     */
    public function addGroup(GroupInterface $group)
    {
        return parent::addGroup($group);
    }

    /**
     * Remove groups
     *
     * @param \ACS\ACSPanelBundle\Entity\FosGroup $groups
     */
    public function removeGroup(GroupInterface $group)
    {
        parent::removeGroup($group);
    }

    /**
     * Add plans
     *
     * @param \ACS\ACSPanelBundle\Entity\Plan $plans
     * @return FosUser
     */
    public function addPlan(\ACS\ACSPanelBundle\Entity\Plan $plans)
    {

        $user_plan = new UserPlan();
        $user_plan->setUplans($plans);
        $user_plan->setPuser($this);
        $this->addPuser($user_plan);

        return $this;
    }

    /**
     * Remove plans
     *
     * @param \ACS\ACSPanelBundle\Entity\Plan $plans
     */
    public function removePlan(\ACS\ACSPanelBundle\Entity\Plan $plans)
    {
        $this->puser->uplans->removeElement($plans);
    }

    /**
     * Get plans
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlans()
    {
        $user_plans = $this->getPuser();
        $plans = new ArrayCollection();
        if (count($user_plans)) {
            foreach ($user_plans as $plan) {
                $plans[] = $plan->getUplans();
            }
        }
        return $plans;
    }

    /**
     * Returns the max value for specified field
     * @return num of resource or -1 if has not this resource
     */
    public function getPlanMax($resource)
    {
        $maxvalue = 0;
        $plans = $this->getPlans();
        $methodname = 'getMax'.$resource;
        foreach ($plans as $plan) {
            $maxvalue += $plan->$methodname();
        }

        if(!$maxvalue)
            return -1;
        return $maxvalue;
    }

    /**
     * Returns the used amount of a resource
     */
    public function getUsedResource($resource, $em)
    {
        $used_amount = 0;

        // Check for delegated used resources
        $child_users = $this->getChildUsers();
        foreach($child_users as $child_user){
            if ($used = $child_user->getPlanMax($resource)) {
                if($used > 0){
                    $used_amount += $used;
                }
            }
        }

        // Check for own used resources
        $repository_name = 'ACSACSPanelBundle:'.$resource;
        $resources = $em->getRepository($repository_name)->findByUser($this);
        $used_amount += count($resources);

        return $used_amount;
    }

    /**
     * Check if user can use specified resource
     *
     */
    public function canUseResource($resource, $em)
    {
        if ($this->getPlanMax($resource) == -1)
            return false;

        if ($this->getPlanMax($resource) > $this->getUsedResource($resource, $em)) {
            return true;
        }

        return false;
    }

    /**
     * Set parent_user
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $parentUser
     * @return FosUser
     */
    public function setParentUser(\ACS\ACSPanelBundle\Entity\FosUser $parentUser = null)
    {
        $this->parent_user = $parentUser;

        return $this;
    }

    /**
     * Get parent_user
     *
     * @return \ACS\ACSPanelBundle\Entity\FosUser
     */
    public function getParentUser()
    {
        return $this->parent_user;
    }

    /**
     * Add child_users
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $childUsers
     * @return FosUser
     */
    public function addChildUser(\ACS\ACSPanelBundle\Entity\FosUser $childUsers)
    {
        $this->child_users[] = $childUsers;

        return $this;
    }

    /**
     * Remove child_users
     *
     * @param \ACS\ACSPanelBundle\Entity\FosUser $childUsers
     */
    public function removeChildUser(\ACS\ACSPanelBundle\Entity\FosUser $childUsers)
    {
        $this->child_users->removeElement($childUsers);
    }

    /**
     * Get child_users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildUsers()
    {
        return $this->child_users;
    }

    /**
     * @ORM\PrePersist
     */
    public function setUserValue()
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $service = $kernel->getContainer()->get('security.context');

        if ($service) {
            if ($service->getToken()) {
                $user = $service->getToken()->getUser();
                // TODO: Get system user and set if its register from register form
                if($user != 'anon.'){
                    return $this->setParentUser($user);
                }else{
                    // $system_user = new FosUser();
                    // $system_user->setId(1);
                    // return $this->setParentUser($system_user);
                }
            }
        }
    }

    /**
     * Get plan
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Add puser
     *
     * @param \ACS\ACSPanelBundle\Entity\UserPlan $puser
     * @return FosUser
     */
    public function addPuser(\ACS\ACSPanelBundle\Entity\UserPlan $puser)
    {
        $this->puser[] = $puser;

        return $this;
    }

    /**
     * Remove puser
     *
     * @param \ACS\ACSPanelBundle\Entity\UserPlan $puser
     */
    public function removePuser(\ACS\ACSPanelBundle\Entity\UserPlan $puser)
    {
        $this->puser->removeElement($puser);
    }

    /**
     * Get puser
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuser()
    {
        return $this->puser;
    }

    /**
     * Set uid
     *
     * @param integer $uid
     * @return FosUser
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set gid
     *
     * @param integer $gid
     * @return FosUser
     */
    public function setGid($gid)
    {
        $this->gid = $gid;

        return $this;
    }

    /**
     * Get gid
     *
     * @return integer
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return FosUser
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return FosUser
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add services
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $services
     * @return FosUser
     */
    public function addService(\ACS\ACSPanelBundle\Entity\Service $services)
    {
        $this->services[] = $services;

        return $this;
    }

    /**
     * Remove services
     *
     * @param \ACS\ACSPanelBundle\Entity\Service $services
     */
    public function removeService(\ACS\ACSPanelBundle\Entity\Service $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add httpdhosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdhosts
     * @return FosUser
     */
    public function addHttpdhost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdhosts)
    {
        $this->httpdhosts[] = $httpdhosts;

        return $this;
    }

    /**
     * Remove httpdhosts
     *
     * @param \ACS\ACSPanelBundle\Entity\HttpdHost $httpdhosts
     */
    public function removeHttpdhost(\ACS\ACSPanelBundle\Entity\HttpdHost $httpdhosts)
    {
        $this->httpdhosts->removeElement($httpdhosts);
    }

    /**
     * Get httpdhosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHttpdhosts()
    {
        return $this->httpdhosts;
    }

    /**
     * Add maildomains
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $maildomains
     * @return FosUser
     */
    public function addMaildomain(\ACS\ACSPanelBundle\Entity\MailDomain $maildomains)
    {
        $this->maildomains[] = $maildomains;

        return $this;
    }

    /**
     * Remove maildomains
     *
     * @param \ACS\ACSPanelBundle\Entity\MailDomain $maildomains
     */
    public function removeMaildomain(\ACS\ACSPanelBundle\Entity\MailDomain $maildomains)
    {
        $this->maildomains->removeElement($maildomains);
    }

    /**
     * Get maildomains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaildomains()
    {
        return $this->maildomains;
    }

    /**
     * Add databases
     *
     * @param \ACS\ACSPanelBundle\Entity\DB $databases
     * @return FosUser
     */
    public function addDatabase(\ACS\ACSPanelBundle\Entity\DB $databases)
    {
        $this->databases[] = $databases;

        return $this;
    }

    /**
     * Remove databases
     *
     * @param \ACS\ACSPanelBundle\Entity\DB $databases
     */
    public function removeDatabase(\ACS\ACSPanelBundle\Entity\DB $databases)
    {
        $this->databases->removeElement($databases);
    }

    /**
     * Get databases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDatabases()
    {
        return $this->databases;
    }

    /**
     * Add settings
     *
     * @param \ACS\ACSPanelBundle\Entity\PanelSetting $settings
     * @return FosUser
     */
    public function addSetting(\ACS\ACSPanelBundle\Entity\PanelSetting $settings)
    {
        $this->settings[] = $settings;

        return $this;
    }

    /**
     * Remove settings
     *
     * @param \ACS\ACSPanelBundle\Entity\PanelSetting $settings
     */
    public function removeSetting(\ACS\ACSPanelBundle\Entity\PanelSetting $settings)
    {
        $this->settings->removeElement($settings);
    }

    /**
     * Get settings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSettings()
    {
        return $this->settings;
    }
    public function getHomedir()
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $settings_manager = $kernel->getContainer()->get('acs.setting_manager');
        $homebase = $settings_manager->getSystemSetting('home_base');
        if($homebase){
            return $homebase.$this->getUsername();
        }

        return null;

    }

    public function setEmail($email){
        $this->email = $email;
    }
}