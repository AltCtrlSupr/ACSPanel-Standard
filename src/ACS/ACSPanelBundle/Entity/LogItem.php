<?php
namespace ACS\ACSPanelBundle\Entity;

use Monolog\Logger;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\LogEntry as LogEntry;

use ACS\ACSPanelBundle\Model\Entity\AclEntity;

/**
 * LogItem
 *
 * @ORM\Entity
 * @Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 */
class LogItem extends LogEntry implements AclEntity
{
    protected $id;
    protected $action;
    protected $loggedAt;
    protected $objectId;
    protected $objectClass;
    protected $version;
    protected $data;
    protected $username;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $object_reference;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \ACS\ACSPanelUsersBundle\Entity\User
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return LogItem
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set object_reference
     *
     * @param string $objectReference
     * @return LogItem
     */
    public function setObjectReference($objectReference)
    {
        $this->object_reference = $objectReference;

        return $this;
    }

    /**
     * Get object_reference
     *
     * @return string
     */
    public function getObjectReference()
    {
        return $this->object_reference;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return LogItem
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return LogItem
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set user
     *
     * @param \ACS\ACSPanelUsersBundle\Entity\User $user
     * @return LogItem
     */
    public function setUser(\ACS\ACSPanelUsersBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ACS\ACSPanelUsersBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    public function getOwners()
    {
        return $this->getUser();
    }

}
