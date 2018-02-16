<?php
namespace Portfolier\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Portfolier\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int An User ID
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Email()
     * @Assert\Length(min = 6, max = 255)
     *
     * @var string An User email
     */
    private $email = '';

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\Regex("/^\w+$/")
     * @Assert\Length(min = 1, max = 255)
     *
     * @var string An User name
     */
    private $name = '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string An User hash
     */
    private $hash = '';


    /**
     * @Assert\Length(min = 6)
     *
     * @var string An User password
     */
    private $password = '';


    /**
     * @ORM\OneToMany(targetEntity="Portfolio", mappedBy="user", cascade={"all"})
     */
    private $portfolios;

    /**
     * Get an User ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set an User ID
     *
     * @param int $id An ID
     *
     * @return Portfolier\Entity\User
     */
    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get an User email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set an User email
     *
     * @param int $email An email
     *
     * @return Portfolier\Entity\User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get an User name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set an User name
     *
     * @param string $name A name
     *
     * @return Portfolier\Entity\User
     */
    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get an User hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Set an User hash
     *
     * @param string $hash A hash
     *
     * @return Portfolier\Entity\User
     */
    public function setHash(string $hash): User
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get an User password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set an User password
     *
     * @param string $password A password
     *
     * @return Portfolier\Entity\User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }
}
