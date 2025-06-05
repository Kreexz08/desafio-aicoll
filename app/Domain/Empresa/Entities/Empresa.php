<?php

namespace App\Domain\Empresa\Entities;

// Deberías importar tus Value Objects aquí
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;

class Empresa
{
    private ?int $id;
    private Nit $nit;
    private string $nombre;
    private string $direccion;
    private string $telefono;
    private Estado $estado;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        Nit $nit,
        string $nombre,
        string $direccion,
        string $telefono,
        Estado $estado,
        ?int $id = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        // Validaciones
        $this->validateNombre($nombre);
        $this->validateDireccion($direccion);
        $this->validateTelefono($telefono);

        $this->id = $id;
        $this->nit = $nit;
        $this->nombre = trim($nombre);
        $this->direccion = trim($direccion);
        $this->telefono = trim($telefono);
        $this->estado = $estado;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNit(): Nit
    {
        return $this->nit;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getDireccion(): string
    {
        return $this->direccion;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getEstado(): Estado
    {
        return $this->estado;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Métodos de negocio
    public function isActive(): bool
    {
        return $this->estado->isActivo();
    }

    public function isInactive(): bool
    {
        return $this->estado->isInactivo();
    }

    public function activate(): void
    {
        $this->estado = Estado::activo();
        $this->touchUpdatedAt();
    }

    public function deactivate(): void
    {
        $this->estado = Estado::inactivo();
        $this->touchUpdatedAt();
    }

    public function updateNombre(string $nombre): void
    {
        $this->validateNombre($nombre);
        $this->nombre = trim($nombre);
        $this->touchUpdatedAt();
    }

    public function updateDireccion(string $direccion): void
    {
        $this->validateDireccion($direccion);
        $this->direccion = trim($direccion);
        $this->touchUpdatedAt();
    }

    public function updateTelefono(string $telefono): void
    {
        $this->validateTelefono($telefono);
        $this->telefono = trim($telefono);
        $this->touchUpdatedAt();
    }

    public function updateEstado(Estado $nuevoEstado): void
    {
        $this->estado = $nuevoEstado;
        $this->touchUpdatedAt();
    }

    private function touchUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }


    private function validateNombre(string $nombre): void
    {
        $nombreTrimmed = trim($nombre);
        if (empty($nombreTrimmed)) {
            throw new InvalidEmpresaDataException('El nombre no puede estar vacío.');
        }
        if (mb_strlen($nombreTrimmed) > 255) {
            throw new InvalidEmpresaDataException('El nombre no puede tener más de 255 caracteres.');
        }
    }

    private function validateDireccion(string $direccion): void
    {
        $direccionTrimmed = trim($direccion);
        if (empty($direccionTrimmed)) {
            throw new InvalidEmpresaDataException('La dirección no puede estar vacía.');
        }
        if (mb_strlen($direccionTrimmed) > 500) {
            throw new InvalidEmpresaDataException('La dirección no puede tener más de 500 caracteres.');
        }
    }

    private function validateTelefono(string $telefono): void
    {
        $telefonoTrimmed = trim($telefono);
        if (empty($telefonoTrimmed)) {
            throw new InvalidEmpresaDataException('El teléfono no puede estar vacío.');
        }
        if (!preg_match('/^\d{10}$/', $telefonoTrimmed)) {
             throw new InvalidEmpresaDataException('El teléfono debe contener exactamente 10 dígitos.');
        }
    }

    // Método para convertir a array
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nit' => $this->nit->getValue(),
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'estado' => $this->estado->getValue(),
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        $nit = new Nit($data['nit']);
        $estado = isset($data['estado']) ? new Estado($data['estado']) : Estado::activo();

        return new self(
            nit: $nit,
            nombre: $data['nombre'],
            direccion: $data['direccion'],
            telefono: $data['telefono'],
            estado: $estado,
            id: $data['id'] ?? null,
            createdAt: isset($data['created_at']) ? new \DateTimeImmutable($data['created_at']) : null,
            updatedAt: isset($data['updated_at']) ? new \DateTimeImmutable($data['updated_at']) : null
        );
    }
}
