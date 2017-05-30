<?php

namespace AppBundle\Entity;

use AppBundle\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Sonata\TranslationBundle\Model\Gedmo\AbstractPersonalTranslatable;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use AppBundle\Traits\DeletedByTrait;

/**
 * Client.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\TranslationEntity(class="AppBundle\Entity\Translations\RoleTranslation")
 */
class Client extends AbstractPersonalTranslatable implements TranslatableInterface
{
    use TimestampableTrait, BlameableEntity, DeletedByTrait;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=20, unique=true)
     */
    private $ip;

    /**
     * @var int
     *
     * @ORM\Column(name="countAttempts", type="integer")
     */
    private $countAttempts;

    /**
     * @var bool
     *
     * @ORM\Column(name="banned", type="boolean")
     */
    private $banned;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return Client
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set countAttempts.
     *
     * @param int $countAttempts
     *
     * @return Client
     */
    public function setCountAttempts($countAttempts)
    {
        $this->countAttempts = $countAttempts;

        return $this;
    }

    /**
     * Get countAttempts.
     *
     * @return int
     */
    public function getCountAttempts()
    {
        return $this->countAttempts;
    }

    /**
     * Set ban.
     *
     * @param bool $banned
     *
     * @return Client
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;

        return $this;
    }

    /**
     * Get banned.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned;
    }

    public function __toString()
    {
        return $this->getIp();
    }
}
