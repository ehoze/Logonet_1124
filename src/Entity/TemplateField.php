<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class TemplateField
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
    private bool $isRequired = false;

    #[ORM\Column(type: Types::STRING, length: 50)]
    #[Assert\Choice(['text', 'date', 'datetime', 'select'])]
    private ?string $fieldType = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $parameters = null;

    #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Template $template = null;

    // getters and setters
    public function __construct()
    {
        $this->template = null;
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
    public function isRequired(): bool
    {
        return $this->isRequired;
    }
    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;
        return $this;
    }
    public function getFieldType(): ?string
    {
        return $this->fieldType;
    }
    public function setFieldType(string $fieldType): self
    {
        $this->fieldType = $fieldType;
        return $this;
    }
    public function getParameters(): ?array
    {
        return $this->parameters;
    }
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }   

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;
        return $this;
    }
}
