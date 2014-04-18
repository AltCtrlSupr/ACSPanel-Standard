<?php


namespace ACS\ACSPanelBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @todo check if is still used
     */
    private $domains;

    /**
     * @var \DateTime
     */
    private $password_changed_at;

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
     * @todo make compatible with resource HttpdAlias
     */
    public function getUsedResource($resource, $em, $alternative_max_resource = null, $additional_cond = null)
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
        if(!$alternative_max_resource)
            $repository_name = 'ACSACSPanelBundle:'.$resource;
        else
            $repository_name = 'ACSACSPanelBundle:'.$alternative_max_resource;

        if(!$additional_cond)
            $resources = $em->getRepository($repository_name)->findByUser($this);
        else
            $resources = $em->getRepository($repository_name)->findBy($additional_cond);

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
	if($this->httpdhosts)
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

    /**
     * Return the home dir based on settings for this user
     */
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

    /**
     * Return the usernames of users childs and sub-childs
     */
    public function getChildUsernames(){
        $user_names[]=$this->getUsername();
        foreach($this->getChildUsers() as $user){
            $unames=$user->getChildUsernames();
            $user_names=array_merge($user_names,$unames);
        }
        return $user_names;
    }

    /**
     * Return the ids of users childs and sub-childs
     * @todo extrange method name, change?
     */
     public function getIdChildIds()
     {
        $user_ids[]=$this->getId();
        foreach($this->getChildUsers() as $user){
            $ids=$user->getIdChildIds();
            $user_ids=array_merge($user_ids,$ids);
        }
        return $user_ids;
     }

    /**
     * @ORM\PrePersist
     */
    public function setGidAndUidValues()
    {
        // we check for the existence in database of the uid
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $usertools = $kernel->getContainer()->get('acs.user.tools');

        $this->setUid($usertools->getAvailableUid());
        $this->setGid($usertools->getAvailableGid());
    }

    /**
     * @ORM\PostPersist
     */
    public function incrementUidSetting()
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $setting_manager = $kernel->getContainer()->get('acs.setting_manager');

        return $setting_manager->setInternalSetting('last_used_uid',$this->getUid());
    }

    /**
     * @ORM\PostPersist
     */
    public function incrementGidSetting()
    {
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        $setting_manager = $kernel->getContainer()->get('acs.setting_manager');

        return $setting_manager->setInternalSetting('last_used_gid',$this->getGid());
    }

    /**
     * Add domains
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domains
     * @return FosUser
     */
    public function addDomain(\ACS\ACSPanelBundle\Entity\Domain $domains)
    {
        $this->domains[] = $domains;

        return $this;
    }

    /**
     * Remove domains
     *
     * @param \ACS\ACSPanelBundle\Entity\Domain $domains
     */
    public function removeDomain(\ACS\ACSPanelBundle\Entity\Domain $domains)
    {
        $this->domains->removeElement($domains);
    }

    /**
     * Get domains
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomains()
    {
        return $this->domains;
    }

    public function setPlainPassword($password)
    {
        $today = new \DateTime();
        $this->setPasswordChangedAt($today);
        parent::setPlainPassword($password);
    }

    /**
     * Set password_changed_at
     *
     * @param \DateTime $passwordChangedAt
     * @return FosUser
     */
    public function setPasswordChangedAt($passwordChangedAt)
    {
        $this->password_changed_at = $passwordChangedAt;

        return $this;
    }

    /**
     * Get password_changed_at
     *
     * @return \DateTime
     */
    public function getPasswordChangedAt()
    {
        return $this->password_changed_at;
    }

    public function userCanSee($security)
    {
        if($security->isGranted('ROLE_SUPER_ADMIN'))
            return true;

        $user_to_check = $this->getParentUser();
        $user = $security->getToken()->getUser();

        if($security->isGranted('ROLE_USER')){
            if($user == $user_to_check)
                return true;
        }

        if($security->isGranted('ROLE_RESELLER')){
            $users = $user->getIdChildIds();
            foreach($users as $childuser){
                if($childuser == $user_to_check->getId())
                    return true;
            }
        }

        return false;

    }

}
