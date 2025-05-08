<?php

class Session
{
  private int $idSession;
  private int $userId;
  private string $sessionToken;
  private string $createdAt;
  private string $expiresAt;

  public function __construct(
    int $idSession,
    int $userId,
    string $sessionToken,
    string $createdAt,
    string $expiresAt
  ) {
    $this->idSession = $idSession;
    $this->userId = $userId;
    $this->sessionToken = $sessionToken;
    $this->createdAt = $createdAt;
    $this->expiresAt = $expiresAt;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }
}
