<?php
// ===== PHP logging naar Render =====
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
ini_set('session.use_strict_mode', '1');

// ===== Veilige sessie =====
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_name('__Host-ppsid');
    session_start();
}

// ===== Helpers =====
function elog($m){ @file_put_contents('php://stderr', "[app] ".str_replace(["\r","\n"], ' ', (string)$m)."\n"); }
function new_error_id(){ return strtoupper(bin2hex(random_bytes(4))); }

function redirect_fail(string $ctx, $details = null, int $http = 302){
    $id = new_error_id();
    // sqlsrv_errors() als details leeg is
    if ($details === null && function_exists('sqlsrv_errors')) {
        $details = sqlsrv_errors();
    }
    // Log ALLES naar Render
    elog("FAIL[$id][$ctx] ".json_encode($details, JSON_UNESCAPED_SLASHES));

    $to = '/error.php?code='.rawurlencode($id);
    if (!headers_sent()) {
        header('Location: '.$to, true, $http);
        exit;
    }
    // Fallback als er al output was
    echo '<!doctype html><meta charset="utf-8"><script>location.href='.json_encode($to).'</script>';
    exit;
}

// Globale vangnetten -> altijd redirecten ipv dumpen
set_error_handler(function($sev,$msg,$file,$line){
    redirect_fail('PHP_ERR', ['msg'=>$msg,'file'=>$file,'line'=>$line], 302);
});
set_exception_handler(function($ex){
    redirect_fail('PHP_EXC', ['msg'=>$ex->getMessage(),'file'=>$ex->getFile(),'line'=>$ex->getLine()], 302);
});
register_shutdown_function(function(){
    $e = error_get_last();
    if ($e && ($e['type'] & (E_ERROR|E_PARSE|E_CORE_ERROR|E_COMPILE_ERROR))) {
        redirect_fail('PHP_FATAL', $e, 302);
    }
});

// Handige base URL
function base_url(){
    $https = (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $proto = $https ? 'https' : 'http';
    $host  = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');
    return $proto.'://'.$host;
}

require __DIR__.'/database.php';
