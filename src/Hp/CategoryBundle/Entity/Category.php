<?php

namespace Hp\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of Category
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */

/**
 * @ORM\Table(name="hp_category")
 * @ORM\Entity(repositoryClass="Hp\CategoryBundle\Entity\Repository\CategoryRepository")
 */
class Category {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="category_name",type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $categoryName;

    /**
     * @ORM\Column(name="id_parent",type="integer", length=11)
     */
    private $idParent;

    /**
     * @ORM\Column(name="category_image",type="string", length=100)
     */
    private $categoryImage;

    /**
     * @Assert\File(
     * maxSize = "5024k"
     * )
     */
    private $file;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Assert\Choice({"1", "0"})
     */
    private $isActive;

    /**
     * @ORM\Column(name="lvl", type="integer",length=11)
     */
    private $lvl;

    private $temp;

    private $randomNo;

    public function __construct()
    {
        $this->randomNo = $this->generateRandomString(10);
    }
    
    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->getId();
    }

    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return type
     */
    public function getCategoryName() {
        return $this->categoryName;
    }

    /**
     * 
     * @param type $categoryImage
     */
    public function setCategoryName($categoryName) {
        $this->categoryName = $categoryName;
    }



    /**
     * 
     * @return type
     */
    public function getIdParent() {
        return $this->idParent;
    }

    /**
     * 
     * @param type $idParent
     */
    public function setIdParent($idParent) {
        $this->idParent = $idParent;
    }

    /**
     * 
     * @return type
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * 
     * @param type $IsActive
     */
    public function setIsActive($IsActive) {
        $this->isActive = $IsActive;
    }

    /**
     * 
     * @return type
     */
    public function getLvl() {
        return $this->lvl;
    }

    /**
     * 
     * @param type $lvl
     */
    public function setLvl($lvl) {
        $this->lvl = $lvl;
    }

    public function getWebPath() {
        
        return null === $this->categoryImage ? null : $this->getUploadDir() . '/' . $this->categoryImage;
    }

    protected function getUploadRootDir() {
        
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/category';
    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        
        
        // check if we have an old image categoryImage
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->categoryImage = "initial";
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        
        
        if (null !== $this->getFile()) {
            
            $this->categoryImage = $this->randomNo . "." . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image categoryImage
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->categoryImage =  $this->randomNo . '.' . $this->getFile()->guessExtension();
        $this->getFile()->move($this->getUploadRootDir(), $this->randomNo . '.' . $this->getFile()->guessExtension());
        
        
        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove() {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (isset($this->temp)) {
            echo $this->getUploadRootDir();
            echo $this->temp;
            exit;
            unlink($this->temp);
        }
    }

    public function getAbsolutePath() {
        
        $absPath = ($this->categoryImage == null ? null : $this->getUploadRootDir() . '/' . $this->randomNo . '.' . $this->categoryImage);
        
        return $absPath;
    }

    /**
     * Generate Random string
     * @param type $length
     * @return string
     */
    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}
