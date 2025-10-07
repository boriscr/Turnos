<style>
    /*loader*/
    /* Estilos para el loader */
    .loader-overlay {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
        height: 400px;
        background-color: var(--light_application_background);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 99999;
        transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.438);
        backdrop-filter: blur(5px);
        opacity: 0;
        visibility: hidden;
    }

    .loader-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Resto del CSS permanece igual */
    .modern-loader {
        text-align: center;
        padding: 20px;
    }

    .loader-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #f0f0f0;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 1.2s linear infinite;
        margin: 0 auto 20px;
        position: relative;
    }

    .loader-spinner::after {
        content: '';
        position: absolute;
        top: -8px;
        left: -8px;
        right: -8px;
        bottom: -8px;
        border: 4px solid transparent;
        border-top: 4px solid var(--general_design_color);
        border-radius: 50%;
        animation: spin 1.6s linear infinite;
        opacity: 0.7;
    }

    .loader-spinner::before {
        content: '';
        position: absolute;
        top: -14px;
        left: -14px;
        right: -14px;
        bottom: -14px;
        border: 4px solid transparent;
        border-top: 4px solid #e74c3c;
        border-radius: 50%;
        animation: spin 2s linear infinite;
        opacity: 0.4;
    }

    .loader-text {
        color: var(--light_text_color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px;
        font-weight: 500;
        margin: 0;
        letter-spacing: 0.5px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Variantes de color */
    .loader-spinner.primary {
        border-top: 4px solid #3498db;
    }

    .loader-spinner.success {
        border-top: 4px solid #2ecc71;
    }

    .loader-spinner.warning {
        border-top: 4px solid #f39c12;
    }

    .loader-spinner.danger {
        border-top: 4px solid #e74c3c;
    }

    .loader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .loader-content .loader-logo {
        width: 150px;
        height: 150px;
        margin-bottom: 15px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Tamaños responsivos */
    @media (max-width: 768px) {
        .loader-overlay {
            width: 90%;
            max-width: 400px;
            height: 300px;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
        }

        .loader-text {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .loader-overlay {
            max-width: 95%;
            height: 250px;
            border-radius: 12px;
        }

        .loader-spinner {
            width: 40px;
            height: 40px;
        }

        .loader-text {
            font-size: 14px;
        }
    }

    /* Tema oscuro */
    @media (prefers-color-scheme: dark) {
        .loader-overlay {
            background-color: var(--dark_application_background);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.167);
        }

        .loader-text {
            color: var(--dark_text_color);
        }

        .loader-spinner {
            border: 4px solid rgba(255, 255, 255, 0.1);
        }
    }
</style>
