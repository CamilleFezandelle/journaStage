<?php

class Session
{
  private int $idSession;
  private int $userId;
  private string $sessionToken;
  private string $createdAt;
  private string $expiresAt;
  private bool $isActive;

  public function __construct(
    int $idSession,
    int $userId,
    string $sessionToken,
    string $createdAt,
    string $expiresAt,
    bool $isActive
  ) {
    $this->idSession = $idSession;
    $this->userId = $userId;
    $this->sessionToken = $sessionToken;
    $this->createdAt = $createdAt;
    $this->expiresAt = $expiresAt;
    $this->isActive = $isActive;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }
}
