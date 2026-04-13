<?php

declare(strict_types=1);

/**
 * Class Book.
 */
class Book
{
    public string $title;

    public function getTitle(): string
    {
        return $this->title;
    }
}
