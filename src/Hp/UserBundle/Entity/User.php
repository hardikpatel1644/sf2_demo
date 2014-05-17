<?php

namespace Hp\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Hp\UserBundle\Entity\Role;

/**
 * Description of User
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */

/**
 * @ORM\Table(name="hp_users")
 * @ORM\Entity()
 */
class User implements AdvancedUserInterface, \Serializable, EquatableInterface {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }

    public function getRoles() {
        return $this->roles->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getUsername() {
        return $this->username;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    /**
     * @inheritDoc
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials() {
        
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
                // $this->username,
                // $this->password,
                // see section on salt below
                // $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                //$this->username,
                // $this->password,
                // see section on salt below
                // $this->salt
                ) = unserialize($serialized);
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    public function isEqualTo(UserInterface $user) {
        return $this->id === $user->getId();
    }

    /**
     * Add roles
     *
     * @param \Hp\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(Role $role) {
        $this->roles[] = $role;
        $role->addUser($this);
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Hp\UserBundle\Entity\Role $roles
     */
    public function removeRole(Role $role) {
        $this->roles->removeElement($role);
    }

}
