<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden | EasyTix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --premium-blue: #071120;
            --premium-gold: #F4D03F;
            --premium-glass: rgba(255, 255, 255, 0.05);
            --premium-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--premium-blue);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(244, 208, 63, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(20, 46, 94, 0.6) 0%, transparent 50%);
            color: #fff;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0;
        }

        .error-container {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 600px;
            padding: 3rem;
            background: rgba(20, 46, 94, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--premium-border);
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .error-code {
            font-size: 120px;
            font-weight: 900;
            background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            letter-spacing: -5px;
            line-height: 1;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--premium-gold);
            margin-bottom: 2rem;
            display: block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        p {
            color: #cbd5e1;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
        }

        .btn-home {
            background: linear-gradient(135deg, #F4D03F 0%, #E67E22 100%);
            color: #000 !important;
            font-weight: 700;
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(244, 208, 63, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(244, 208, 63, 0.5);
        }

        .bg-glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--premium-gold);
            filter: blur(150px);
            opacity: 0.1;
            border-radius: 50%;
            z-index: 1;
        }

        .glow-1 { top: 20%; left: 20%; }
        .glow-2 { bottom: 20%; right: 20%; }
    </style>
</head>
<body>
    <div class="bg-glow glow-1"></div>
    <div class="bg-glow glow-2"></div>

    <div class="error-container">
        <i class="fas fa-shield-alt error-icon"></i>
        <div class="error-code">403</div>
        <h1>Akses Ditolak</h1>
        <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Ini adalah area terbatas untuk peran yang berbeda.</p>
        
        <a href="/" class="btn-home">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <!-- Background Elements -->
    <div style="position: fixed; inset: 0; pointer-events: none; opacity: 0.05; background-image: url('https://www.transparenttextures.com/patterns/carbon-fibre.png');"></div>
</body>
</html>
