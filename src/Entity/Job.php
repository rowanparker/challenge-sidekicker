<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['job:collection:get']
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['job:item:get']
            ]
        ],
    ],
    attributes: [
        'pagination_items_per_page' => 500,
    ]
)]
#[ORM\Entity]
class Job
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, nullable: false)]
    #[Groups([
        'job:collection:get',
        'job:item:get',
    ])]
    #[Assert\NotBlank(message: 'Title can not be empty')]
    #[Assert\Length(max: 100, maxMessage: 'Title can not exceed 100 characters')]
    private string $title;

    #[ORM\Column(type: 'text', nullable: false)]
    #[Groups([
        'job:item:get',
    ])]
    #[Assert\NotBlank(message: 'Description can not be empty')]
    #[Assert\Length(max: 500, maxMessage: 'Description can not exceed 500 characters')]
    private string $description;

    #[ORM\Column(type: 'datetime_immutable', nullable: false)]
    #[Groups([
        'job:collection:get',
        'job:item:get',
    ])]
    #[Assert\NotBlank(message: 'Date can not be empty')]
    private \DateTimeImmutable $date;

    #[ORM\Column(nullable: false)]
    #[Groups([
        'job:collection:get',
        'job:item:get',
    ])]
    #[Assert\NotBlank(message: 'Location must be Sydney, Melbourne, Brisbane or Perth')]
    #[Assert\Choice(
        choices: ['Sydney','Melbourne','Brisbane','Perth'],
        message: 'Location must be Sydney, Melbourne, Brisbane or Perth')
    ]
    private string $location;

    #[ORM\OneToMany(
        mappedBy: 'job',
        targetEntity: JobApplicant::class,
        orphanRemoval: true,
    )]
    #[Groups([
        'job:item:get',
    ])]
    private Collection $jobApplicants;

    public function __construct()
    {
        $this->jobApplicants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
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
            $jobApplicant->setJob($this);
        }

        return $this;
    }

    public function removeJobApplicant(JobApplicant $jobApplicant): self
    {
        if ($this->jobApplicants->removeElement($jobApplicant)) {
            // set the owning side to null (unless already changed)
            if ($jobApplicant->getJob() === $this) {
                $jobApplicant->setJob(null);
            }
        }

        return $this;
    }
}
