<?php

namespace Classes;
use Classes\Tag;
class Course
{
    public function __construct(
        private int $id,
        private string $title,
        private string $description,
        private string $thumbnail,
        private string $video,
        private string $document,
        private int $category_id,
        private string $category_name,
        private int $user_id,
        private array $tags,
        private string $created_at,
        private string $user_name,
        private string $user_email
    ) {
        forEach($tags as $tag) {
            if(!$tag instanceof Tag){
                throw new \InvalidArgumentException("All tags should be instance of Tag class.");
            }
        }
    }

    public function toArray() {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "description" => $this->getDescription(),
            "thumbnail" => $this->getThumbnail(),
            "video" => $this->getVideo(),
            "document" => $this->getDocument(),
            "category_id" => $this->getCategoryId(),
            "category_name" => $this->getCategoryName(),
            "user_id" => $this->getUserId(),
            "tags" => array_map(function ($tag) {
                return $tag->toArray(); // Ensure tags are arrays
            }, $this->getTags() ?? [])
        ];
    }

    
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserName(): string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getUserEmail(): string
    {
        return $this->user_email;
    }

    public function setUserEmail(string $user_email): self
    {
        $this->user_email = $user_email;

        return $this;
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function getDocument(): string
    {
        return $this->document;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getTags(): array
    {
        return $this->tags;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setThumbnail(string $thumbnail): void
    {
        $this->thumbnail = $thumbnail;
    }

    public function setVideo(string $video): void
    {
        $this->video = $video;
    }

    public function setDocument(string $document): void
    {
        $this->document = $document;
    }

    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
    public function setTag (Tag $newTag){
        $this->tags[] = $newTag;
    } 
}
