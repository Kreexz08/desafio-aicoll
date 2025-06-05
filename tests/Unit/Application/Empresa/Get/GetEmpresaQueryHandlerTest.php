<?php

namespace Tests\Unit\Application\Empresa\Get;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Get\GetEmpresaQuery;
use App\Application\Empresa\Get\GetEmpresaQueryHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\EmpresaNotFoundException;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Domain\Empresa\ValueObjects\Estado;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test;

class GetEmpresaQueryHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private GetEmpresaQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new GetEmpresaQueryHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_devolver_una_entidad_empresa_cuando_se_encuentra(): void
    {
        // 1. ARRANGE
        $nitToFind = '900111222-1';
        $query = new GetEmpresaQuery($nitToFind);

        // Simulamos que el repositorio encuentra y devuelve una entidad.
        $empresaEncontrada = new Empresa(
            id: 1,
            nit: new Nit($nitToFind),
            nombre: 'Empresa Encontrada',
            direccion: 'Dirección de Prueba',
            telefono: '3001112222',
            estado: Estado::activo()
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn($empresaEncontrada);

        // 2. ACT
        $result = $this->handler->handle($query);

        // 3. ASSERT
        $this->assertInstanceOf(Empresa::class, $result);
        $this->assertSame($empresaEncontrada, $result); // Verificamos que sea el mismo objeto.
        $this->assertEquals($nitToFind, $result->getNit()->getValue());
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_la_empresa_no_se_encuentra(): void
    {
        // 1. ARRANGE
        $query = new GetEmpresaQuery('000000000-0');

        // Simulamos que el repositorio devuelve null.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findByNit')
            ->willReturn(null);

        // Esperamos la excepción.
        $this->expectException(EmpresaNotFoundException::class);

        // 2. ACT
        $this->handler->handle($query);
    }
}
