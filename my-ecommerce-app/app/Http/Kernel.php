protected $middlewareAliases = [
    // ... other aliases like 'auth', 'guest' ...
    'admin' => \App\Http\Middleware\CheckIsAdmin::class, // <-- Add this line
];
