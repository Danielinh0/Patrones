<?php
use App\Models\Tarjeta;
use App\Models\Transaccion;

try {
    // Re-seed to clean database state
    Artisan::call('migrate:fresh', ['--seed' => true]);
    echo "Database re-seeded successfully.\n\n";

    // 1. Get the General card (Juan Pérez, initial: 150)
    $tarjetaGeneral = Tarjeta::where('tipo', 'GENERAL')->first();
    echo "GENERAL CARD - ID: " . $tarjetaGeneral->id_tarjeta . "\n";
    echo "Initial balance: " . $tarjetaGeneral->saldo_actual . "\n";
    
    // Recharge 50
    echo "Recharging 50...\n";
    $tarjetaGeneral->recargarSaldo(50.0);
    echo "After recharge balance: " . $tarjetaGeneral->fresh()->saldo_actual . "\n";
    if ($tarjetaGeneral->fresh()->saldo_actual != 200) {
        throw new Exception("General recharge failed.");
    }
    
    // Calculate and Pay travel via Strategy
    $strategy = $tarjetaGeneral->getTarifaStrategy();
    $fare = $strategy->calcularTarifa($tarjetaGeneral);
    echo "Calculated fare (Strategy): $" . $fare . "\n";
    if ($fare != 10.0) {
        throw new Exception("Strategy calculated wrong fare for GENERAL card.");
    }
    echo "Paying travel...\n";
    $tarjetaGeneral->pagarViaje($fare);
    echo "After travel balance: " . $tarjetaGeneral->fresh()->saldo_actual . "\n";
    if ($tarjetaGeneral->fresh()->saldo_actual != 190) {
        throw new Exception("General travel payment failed.");
    }
    
    echo "----------------------------------------\n";
    
    // 2. Get Student card (María García, initial: 18)
    $tarjetaEstudiante = Tarjeta::where('tipo', 'ESTUDIANTE')->first();
    echo "STUDENT CARD - ID: " . $tarjetaEstudiante->id_tarjeta . "\n";
    echo "Initial balance: " . $tarjetaEstudiante->saldo_actual . "\n";
    
    // Calculate and Pay travel via Strategy
    $strategyEst = $tarjetaEstudiante->getTarifaStrategy();
    $fareEst = $strategyEst->calcularTarifa($tarjetaEstudiante);
    echo "Calculated fare (Strategy): $" . $fareEst . "\n";
    if ($fareEst != 5.0) {
        throw new Exception("Strategy calculated wrong fare for ESTUDIANTE card.");
    }
    echo "Paying travel...\n";
    $tarjetaEstudiante->pagarViaje($fareEst);
    echo "After travel balance: " . $tarjetaEstudiante->fresh()->saldo_actual . "\n";
    if ($tarjetaEstudiante->fresh()->saldo_actual != 13) {
        throw new Exception("Student travel payment failed.");
    }
    
    // Try to pay 20 (insufficient)
    try {
        echo "Trying to pay 20 (should fail due to balance)...\n";
        $tarjetaEstudiante->pagarViaje(20.0);
        throw new Exception("Student travel did not fail as expected.");
    } catch (\Exception $e) {
        echo "Expected error: " . $e->getMessage() . "\n";
    }

    echo "----------------------------------------\n";
    
    // 3. Get Blocked card (Andrés López, initial: 50)
    $tarjetaBloqueada = Tarjeta::where('tipo', 'ADULTO_MAYOR')->first();
    echo "BLOCKED CARD - ID: " . $tarjetaBloqueada->id_tarjeta . "\n";
    echo "Initial balance: " . $tarjetaBloqueada->saldo_actual . "\n";
    
    // Recharge 20 (blocked cards can be recharged)
    echo "Recharging 20...\n";
    $tarjetaBloqueada->recargarSaldo(20.0);
    echo "After recharge balance: " . $tarjetaBloqueada->fresh()->saldo_actual . "\n";
    
    // Try to pay (should fail because it is blocked)
    $strategyBM = $tarjetaBloqueada->getTarifaStrategy();
    $fareBM = $strategyBM->calcularTarifa($tarjetaBloqueada);
    echo "Calculated fare (Strategy): $" . $fareBM . "\n";
    if ($fareBM != 1.0) {
        throw new Exception("Strategy calculated wrong fare for ADULTO_MAYOR card.");
    }
    try {
        echo "Trying to pay travel (should fail due to Blocked State)...\n";
        $tarjetaBloqueada->pagarViaje($fareBM);
        throw new Exception("Blocked travel did not fail as expected.");
    } catch (\Exception $e) {
        echo "Expected error: " . $e->getMessage() . "\n";
    }

    echo "----------------------------------------\n";

    // 4. Get Expired card (Ana Sánchez, initial: 0)
    $tarjetaVencida = Tarjeta::where('tipo', 'TURISTA')->first();
    echo "EXPIRED CARD - ID: " . $tarjetaVencida->id_tarjeta . "\n";
    echo "Initial balance: " . $tarjetaVencida->saldo_actual . "\n";
    
    // Try to recharge (should fail because it is expired)
    try {
        echo "Trying to recharge 20 (should fail due to Expired State)...\n";
        $tarjetaVencida->recargarSaldo(20.0);
        throw new Exception("Expired recharge did not fail as expected.");
    } catch (\Exception $e) {
        echo "Expected error: " . $e->getMessage() . "\n";
    }

    echo "\nAll Strategy and State integration tests passed successfully!\n";

} catch (\Exception $e) {
    echo "FATAL TEST ERROR: " . $e->getMessage() . "\n";
}
