<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class JobApplicant
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(
        targetEntity: Job::class,inversedBy: 'jobApplicants'
    )]
    #[ORM\JoinColumn(nullable: false)]
    private Job $job;

    #[ORM\ManyToOne(
        targetEntity: Applicant::class,inversedBy: 'jobApplicants'
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        'job:item:get',
    ])]
    private Applicant $applicant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getApplicant(): ?Applicant
    {
        return $this->applicant;
    }

    public function setApplicant(?Applicant $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }
}
