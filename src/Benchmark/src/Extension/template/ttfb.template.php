<?php

// disable garbage collection
gc_disable();

// repress any output from the user scripts
ob_start();

$class = '{{ class }}';
$file = '{{ file }}';
$beforeMethods = {{ beforeMethods }};
$afterMethods = {{ afterMethods }};
$bootstrap = '{{ bootstrap }}';
$parameters = {{ parameters }};
$warmup = {{ warmup }};

if ($bootstrap) {
    call_user_func(function () use ($bootstrap) {
        require_once($bootstrap);
    });
}

require_once($file);

$benchmark = new $class();

foreach ($beforeMethods as $beforeMethod) {
    $benchmark->$beforeMethod($parameters);
}

// warmup if required
if ($warmup) {
    for ($i = 0; $i < $warmup; $i++) {
        $benchmark->{{ subject }}($parameters);
    }
}

$time = benchmark($benchmark, $parameters);

foreach ($afterMethods as $afterMethod) {
    $benchmark->$afterMethod($parameters);
}

$buffer = ob_get_contents();
ob_end_clean();

echo serialize([
    'mem' => [
        // observer effect - getting memory usage affects memory usage. order
        // counts, peak is probably the best metric.
        'peak' => memory_get_peak_usage(),
        'final' => memory_get_usage(),
        'real' => memory_get_usage(true),
    ],
    'time' => (int) $time,
    'buffer' => $buffer,
]);

function benchmark($benchmark, $parameters)
{
    // run the benchmarks: note that passing arguments to the method slightly increases
    // the calltime, so we explicitly do one thing or the other depending on if parameters
    // are provided.
    if ($parameters) {
        $result = 0;

        for ($i = 0; $i < {{ revolutions }}; $i++) {
            $result += $benchmark->{{ subject }}($parameters);
        }
    } else {
        $result = 0;

        for ($i = 0; $i < {{ revolutions }}; $i++) {
            $result += $benchmark->{{ subject }}();
        }
    }

    return $result  * 1000000 ;
}

exit(0);
