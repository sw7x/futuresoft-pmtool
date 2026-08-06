<?php

namespace Modules\Project\Domain;

class Client
{
    private string $id;
    private string $name;
    private string $contactEmail;
    private string $address;

    public function __construct(string $name, string $contactEmail)
    {
        $this->name = $name;
        $this->contactEmail = $contactEmail;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
