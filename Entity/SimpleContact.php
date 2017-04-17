<?php

namespace EHDev\Bundle\BasicsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EHDev\Bundle\BasicsBundle\Entity\Traits\BUOwnerTrait;
use EHDev\Bundle\BasicsBundle\Model\ExtendSimpleContact;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

/**
 *
 * @package EHDev\Bundle\BasicsBundle\Entity
 */

/**
 * Class SimpleContact
 *
 * @ORM\Entity(repositoryClass="EHDev\Bundle\BasicsBundle\Entity\Repository\SimpleContactRepository")
 * @ORM\Table(
 *      name="ehdev_basics_contact",
 *      indexes={
 *          @ORM\Index(name="contact_name_idx",columns={"last_name", "first_name"})
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *      routeName="ehdev_simplecontact_index",
 *      routeView="ehdev_simplecontact_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="fa-users",
 *              "contact_information"={
 *                  "email"={
 *                      {"fieldName"="primaryEmail"}
 *                  },
 *                  "phone"={
 *                      {"fieldName"="primaryPhone"}
 *                  }
 *              }
 *          },
 *          "ownership"={
 *            "owner_type"="BUSINESS_UNIT",
 *            "owner_field_name"="owner",
 *            "owner_column_name"="business_unit_owner_id",
 *            "organization_field_name"="organization",
 *            "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="",
 *              "category"="ehdev_account_management"
 *          },
 *          "form"={
 *              "form_type"="ehdev_simplecontact_select",
 *              "grid_name"="ehdev-simplecontact-select-grid",
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "grid"={
 *              "default"="ehdev-simplecontacts-grid",
 *              "context"="ehdev-simplecontacts-for-context-grid"
 *          },
 *          "tag"={
 *              "enabled"=true
 *          }
 *      }
 * )
 */
class SimpleContact extends ExtendSimpleContact
{
    use BUOwnerTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=8, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true},
     *          "entity"={"contact_information"="email"}
     *      }
     * )
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "dataaudit"={"auditable"=true}
     *     }
     * )
     */
    protected $facebook;

    /**
     * @var \EHDev\Bundle\BasicsBundle\Entity\SimpleContactAddress
     * @ORM\OneToOne(targetEntity="EHDev\Bundle\BasicsBundle\Entity\SimpleContactAddress", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=true)
     */
    protected $address;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return SimpleContact
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     *
     * @return SimpleContact
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return SimpleContact
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     *
     * @return SimpleContact
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     *
     * @return SimpleContact
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return SimpleContact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return SimpleContact
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $skype
     *
     * @return SimpleContact
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     *
     * @return SimpleContact
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     *
     * @return SimpleContact
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return \EHDev\Bundle\BasicsBundle\Entity\SimpleContactAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \EHDev\Bundle\BasicsBundle\Entity\SimpleContactAddress $address
     *
     * @return SimpleContact
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
