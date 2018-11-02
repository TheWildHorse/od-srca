<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="App\Repository\WishesRepository")
 */
class Wishes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wish;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $realizeWish;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGranted = false;

    /**
     * @Assert\Image()
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $wishImage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $realizeEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $realizePhone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $wishPhone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWish(): ?string
    {
        return $this->wish;
    }

    public function setWish(?string $wish): self
    {
        $this->wish = $wish;

        return $this;
    }

    public function getRealizeWish(): ?string
    {
        return $this->realizeWish;
    }

    public function setRealizeWish(?string $realizeWish): self
    {
        $this->realizeWish = $realizeWish;

        return $this;
    }

    public function getIsGranted(): ?bool
    {
        return $this->isGranted;
    }

    public function setIsGranted(bool $isGranted): self
    {
        $this->isGranted = $isGranted;

        return $this;
    }

    public function getWishImage()
    {
        return $this->wishImage;
    }

    public function setWishImage($wishImage): self
    {
        $this->wishImage = $wishImage;

        return $this;
    }

    public function getRealizeEmail(): ?string
    {
        return $this->realizeEmail;
    }

    public function setRealizeEmail(?string $realizeEmail): self
    {
        $this->realizeEmail = $realizeEmail;

        return $this;
    }

    public function getRealizePhone(): ?string
    {
        return $this->realizePhone;
    }

    public function setRealizePhone(?string $realizePhone): self
    {
        $this->realizePhone = $realizePhone;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getWishPhone(): ?string
    {
        return $this->wishPhone;
    }

    public function setWishPhone(string $wishPhone): self
    {
        $this->wishPhone = $wishPhone;

        return $this;
    }
}
