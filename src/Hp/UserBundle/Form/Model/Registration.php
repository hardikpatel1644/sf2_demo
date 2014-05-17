<?php

namespace Hp\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Hp\UserBundle\Entity\User;

/**
 * Description of Registration
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */
class Registration {

    /**
     * @Assert\Type(type="Hp\UserBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    public function getTermsAccepted() {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted) {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }

}
