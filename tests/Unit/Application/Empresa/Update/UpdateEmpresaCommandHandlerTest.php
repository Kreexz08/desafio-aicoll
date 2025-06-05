<?php

namespace Tests\Unit\Application\Empresa\Update;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Update\UpdateEmpresaCommand;
use App\Application\Empresa\Update\UpdateEmpresaCommandHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\Exceptions\NoFieldsToUpdateException;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test; // ¡LA LÍNEA IMPORTANTE QUE FALTABA!

class UpdateEmpresaCommandHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private UpdateEmpresaCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new UpdateEmpresaCommandHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_actualizar_y_devolver_la_empresa_exitosa(): void
    {
        // 1. ARRANGE
        $nitToFind = '900111222-1';
        $command = new UpdateEmpresaCommand(
            nit: $nitToFind,
            nombre: 'Nombre Actualizado SA',
            estado: 'Inactivo'
        );

        $originalEmpresa = new Empresa(
            id: 1,
            nit: new Nit($nitToFind),
            nombre: 'Nombre Original',
            direccion: 'Dirección Original',
            telefono: '3001234567',
            estado: Estado::activo()
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn($originalEmpresa);

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturnCallback(fn(Empresa $empresa) => $empresa);

        // 2. ACT
        $updatedEmpresa = $this->handler->handle($command);

        // 3. ASSERT
        $this->assertInstanceOf(Empresa::class, $updatedEmpresa);
        $this->assertEquals('Nombre Actualizado SA', $updatedEmpresa->getNombre());
        $this->assertTrue($updatedEmpresa->isInactive());
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_la_empresa_no_se_encuentra(): void
    {
        // 1. ARRANGE
        $command = new UpdateEmpresaCommand(nit: '900111222-1');

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn(null);

        $this->expectException(EmpresaNotFoundException::class);

        $this->empresaRepositoryMock->expects($this->never())->method('save');

        // 2. ACT
        $this->handler->handle($command);
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_no_hay_datos_para_actualizar(): void
    {
        // 1. ARRANGE
        $command = new UpdateEmpresaCommand(nit: '900111222-1');

        $empresaExistente = new Empresa(id: 1, nit: new Nit($command->nit), nombre: 'Nombre', direccion: 'Dirección', telefono: '1234567890', estado: Estado::activo());
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn($empresaExistente);

        $this->expectException(NoFieldsToUpdateException::class);
        $this->empresaRepositoryMock->expects($this->never())->method('save');

        // 2. ACT
        $this->handler->handle($command);
    }
}
