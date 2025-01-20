<?php
namespace  Classes;
class Tag
{
    public function __construct(
        private int $id,
        private string $name
    ) {}

    public function toArray(){
        return [
            "id" => $this->id,
            "name" => $this->name
        ];
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
