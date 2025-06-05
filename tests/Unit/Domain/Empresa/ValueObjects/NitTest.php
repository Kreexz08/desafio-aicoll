<?php

namespace Tests\Unit\Domain\Empresa\ValueObjects;

use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use App\Domain\Empresa\ValueObjects\Nit;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class NitTest extends TestCase
{
    #[Test]
    #[DataProvider('validNitProvider')]
    public function se_puede_crear_un_nit_valido(string $nitValue): void
    {
        $nit = new Nit($nitValue);
        $this->assertInstanceOf(Nit::class, $nit);
        $this->assertEquals($nitValue, $nit->getValue());
        $this->assertEquals((string) $nit, $nitValue);
    }

    public static function validNitProvider(): array
    {
        return [
            'nit valido 1' => ['900123456-1'],
            'nit valido 2' => ['800555777-9'],
        ];
    }

    #[Test]
    public function nit_no_puede_estar_vacio(): void
    {
        $this->expectException(InvalidEmpresaDataException::class);
        $this->expectExceptionMessage("El campo 'NIT' no puede estar vacío");
        new Nit('');
    }



    #[Test]
    #[DataProvider('invalidNitFormatProvider')]
    public function nit_con_formato_incorrecto_lanza_excepcion(string $invalidNit): void
    {
        $this->expectException(InvalidEmpresaDataException::class);
        $this->expectExceptionMessage("El NIT '{$invalidNit}' no tiene un formato válido");
        new Nit($invalidNit);
    }

    public static function invalidNitFormatProvider(): array
    {
        return [
            'sin guion pero con longitud correcta' => ['12345678901'],
            'con letras' => ['ABC123456-1'],
            'guion mal ubicado' => ['12345-67890'],
        ];
    }

    #[Test]
    public function nit_de_10_caracteres_lanza_excepcion_de_longitud_correcta(): void
    {
        $nitCorto = '12345678-1'; // 10 caracteres
        $this->expectException(InvalidEmpresaDataException::class);
        $this->expectExceptionMessage("El campo 'NIT' debe tener exactamente 11 caracteres, pero se recibieron 10.");
        new Nit($nitCorto);
    }

    #[Test]
    public function metodo_equals_compara_correctamente(): void
    {
        $nit1 = new Nit('900111222-1');
        $nit2 = new Nit('900111222-1');
        $nit3 = new Nit('800222333-2');
        $this->assertTrue($nit1->equals($nit2));
        $this->assertFalse($nit1->equals($nit3));
    }
}
