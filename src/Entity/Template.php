<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Template
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[a-z]+(?:[A-Z][a-z]*)*$/')]  // dla camelCase
    private ?string $systemName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank]
    private ?string $displayName = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isVisible = true;

    #[ORM\OneToMany(targetEntity: TemplateField::class, mappedBy: 'template', cascade: ['persist', 'remove'])]
    private Collection $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(string $systemName): self
    {
        $this->systemName = $systemName;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function isVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return Collection<int, TemplateField>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(TemplateField $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setTemplate($this);
        }
        return $this;
    }

    public function removeField(TemplateField $field): self
    {
        if ($this->fields->removeElement($field)) {
            if ($field->getTemplate() === $this) {
                $field->setTemplate(null);
            }
        }
        return $this;
    }

    public function getFieldBySystemName(string $systemName): ?TemplateField
    {
        foreach ($this->fields as $field) {
            if ($field->getSystemName() === $systemName) {
                return $field;
            }
        }
        
        return null;
    }
}