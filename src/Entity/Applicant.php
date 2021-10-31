<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Applicant
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[Groups([
        'job:item:get',
    ])]
    #[Assert\NotBlank(message: 'Name can not be empty')]
    private string $name;

    #[ORM\OneToMany(
        mappedBy: 'applicant',
        targetEntity: JobApplicant::class,
        orphanRemoval: true,
    )]
    private Collection $jobApplicants;

    public function __construct()
    {
        $this->jobApplicants = new ArrayCollection();
    }

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

    /**
     * @return Collection|JobApplicant[]
     */
    public function getJobApplicants(): Collection
    {
        return $this->jobApplicants;
    }

    public function addJobApplicant(JobApplicant $jobApplicant): self
    {
        if (!$this->jobApplicants->contains($jobApplicant)) {
            $this->jobApplicants[] = $jobApplicant;
            $jobApplicant->setApplicant($this);
        }

        return $this;
    }

    public function removeJobApplicant(JobApplicant $jobApplicant): self
    {
        if ($this->jobApplicants->removeElement($jobApplicant)) {
            // set the owning side to null (unless already changed)
            if ($jobApplicant->getApplicant() === $this) {
                $jobApplicant->setApplicant(null);
            }
        }

        return $this;
    }
}
