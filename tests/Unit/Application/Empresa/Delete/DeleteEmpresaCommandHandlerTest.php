<?php

namespace Tests\Unit\Application\Empresa\Delete;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Delete\DeleteEmpresaCommand;
use App\Application\Empresa\Delete\DeleteEmpresaCommandHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\CannotDeleteActiveEmpresaException;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test;

class DeleteEmpresaCommandHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private DeleteEmpresaCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new DeleteEmpresaCommandHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_eliminar_una_empresa_inactiva_correctamente(): void
    {
        // 1. ARRANGE
        $nitToDelete = '900222333-2';
        $command = new DeleteEmpresaCommand($nitToDelete);

        // Simulamos que el repositorio encuentra una empresa INACTIVA.
        $inactiveEmpresa = new Empresa(
            id: 2,
            nit: new Nit($nitToDelete),
            nombre: 'Empresa Inactiva a Borrar',
            direccion: 'Alguna',
            telefono: '3101234567',
            estado: Estado::inactivo() // ¡Importante: estado inactivo!
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn($inactiveEmpresa);

        // Esperamos que el método 'delete' sea llamado con la entidad encontrada y devuelva true.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('delete')
            ->with($this->identicalTo($inactiveEmpresa))
            ->willReturn(true);

        // 2. ACT
        $result = $this->handler->handle($command);

        // 3. ASSERT
        $this->assertTrue($result);
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_al_intentar_eliminar_una_empresa_activa(): void
    {
        // 1. ARRANGE
        $nitToDelete = '900111222-1';
        $command = new DeleteEmpresaCommand($nitToDelete);

        // Simulamos que el repositorio encuentra una empresa ACTIVA.
        $activeEmpresa = new Empresa(
            id: 1,
            nit: new Nit($nitToDelete),
            nombre: 'Empresa Activa Intocable',
            direccion: 'Alguna',
            telefono: '3001234567',
            estado: Estado::activo() // ¡Importante: estado activo!
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn($activeEmpresa);

        // Esperamos la excepción de regla de negocio.
        $this->expectException(CannotDeleteActiveEmpresaException::class);

        // Verificamos que 'delete' NUNCA sea llamado.
        $this->empresaRepositoryMock->expects($this->never())->method('delete');

        // 2. ACT
        $this->handler->handle($command);
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_la_empresa_a_eliminar_no_existe(): void
    {
        // 1. ARRANGE
        $command = new DeleteEmpresaCommand('000000000-0');

        // Simulamos que el repositorio NO encuentra la empresa.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn(null);

        // Esperamos la excepción de no encontrado.
        $this->expectException(EmpresaNotFoundException::class);

        // 2. ACT
        $this->handler->handle($command);
    }
}
