/**
 * Checkin Loading Manager
 * Sistema de gestión de indicador de carga para formularios de check-in
 * Author: Departamento Desarrollo MABEL
 * Version: 1.0
 * Dependencies: checkin-loading.css
 */

(function() {
    'use strict';

    /**
     * Clase para manejar el sistema de carga
     */
    class CheckinLoadingManager {
        constructor(options = {}) {
            this.config = {
                overlayId: 'loadingOverlay',
                formSelector: 'form',
                submitButtonSelector: 'button[type="submit"]',
                pasaporteInputId: 'pas',
                fadeTimeout: 300,
                ...options
            };
            
            this.form = null;
            this.submitButton = null;
            this.loadingOverlay = null;
            this.pasaporteInput = null;
            
            this.init();
        }

        /**
         * Inicializa el sistema de carga
         */
        init() {
            // Esperar a que el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.setup());
            } else {
                this.setup();
            }
        }

        /**
         * Configura los elementos y eventos
         */
        setup() {
            this.getElements();
            this.bindEvents();
            this.setupInitialState();
        }

        /**
         * Obtiene referencias a los elementos del DOM
         */
        getElements() {
            this.form = document.querySelector(this.config.formSelector);
            this.submitButton = document.querySelector(this.config.submitButtonSelector);
            this.loadingOverlay = document.getElementById(this.config.overlayId);
            this.pasaporteInput = document.getElementById(this.config.pasaporteInputId);

            // Solo mostrar warnings si hay un formulario pero faltan elementos críticos
            if (this.form && this.submitButton && !this.loadingOverlay) {
                // Solo advertir si parece que debería haber loading (página con formulario completo)
                console.info('CheckinLoadingManager: Sistema iniciado sin overlay de carga. Funcionalidad limitada.');
            }
        }

        /**
         * Configura los eventos
         */
        bindEvents() {
            if (!this.form || !this.submitButton) return;

            // Evento de envío del formulario (siempre configurar para procesamiento de pasaporte)
            this.form.addEventListener('submit', (event) => this.handleFormSubmit(event));
            
            // Solo configurar eventos de loading si hay overlay disponible
            if (this.loadingOverlay) {
                // Eventos para ocultar loading
                window.addEventListener('pageshow', () => this.hideLoading());
                window.addEventListener('focus', () => this.hideLoading());
                
                // Evento para limpiar loading en caso de error
                window.addEventListener('beforeunload', () => this.hideLoading());
            }
        }

        /**
         * Configura el estado inicial
         */
        setupInitialState() {
            // Asegurar que el loading esté oculto al inicio
            this.hideLoading();
        }

        /**
         * Maneja el evento de envío del formulario
         * @param {Event} event - Evento de envío
         */
        handleFormSubmit(event) {
            // Validar que el formulario sea válido antes de mostrar loading
            if (!this.form.checkValidity()) {
                return; // Si no es válido, no mostramos loading y dejamos que la validación nativa funcione
            }
            
            // Procesar pasaporte si existe
            this.processPassportInput();
            
            // Mostrar indicador de carga
            this.showLoading();
        }

        /**
         * Procesa el input de pasaporte convirtiéndolo a mayúsculas
         */
        processPassportInput() {
            if (this.pasaporteInput && this.pasaporteInput.value) {
                this.pasaporteInput.value = this.pasaporteInput.value.toUpperCase();
            }
        }

        /**
         * Muestra el indicador de carga
         */
        showLoading() {
            // Si no hay elementos necesarios, retornar silenciosamente
            if (!this.submitButton || !this.form) return;

            try {
                // Siempre deshabilitar el botón y el formulario para evitar doble envío
                this.submitButton.disabled = true;
                this.submitButton.classList.add('btn-loading');
                this.form.classList.add('form-loading');
                
                // Solo mostrar overlay si existe
                if (this.loadingOverlay) {
                    // Mostrar overlay de carga con animación
                    this.loadingOverlay.style.display = 'flex';
                    
                    // Usar requestAnimationFrame para asegurar que el display se aplique antes de la animación
                    requestAnimationFrame(() => {
                        this.loadingOverlay.classList.add('show');
                    });
                    
                    // Deshabilitar scroll del body
                    document.body.classList.add('no-scroll');
                }

                // Dispatch evento personalizado
                this.dispatchEvent('checkin:loadingStart');

            } catch (error) {
                console.error('Error al mostrar loading:', error);
                this.hideLoading(); // Fallback en caso de error
            }
        }

        /**
         * Oculta el indicador de carga
         */
        hideLoading() {
            // Si no hay elementos necesarios, retornar silenciosamente
            if (!this.submitButton || !this.form) return;

            try {
                // Siempre habilitar el botón y el formulario
                this.submitButton.disabled = false;
                this.submitButton.classList.remove('btn-loading');
                this.form.classList.remove('form-loading');
                
                // Solo ocultar overlay si existe
                if (this.loadingOverlay) {
                    // Ocultar overlay de carga con animación
                    this.loadingOverlay.classList.remove('show');
                    
                    setTimeout(() => {
                        if (!this.loadingOverlay.classList.contains('show')) {
                            this.loadingOverlay.style.display = 'none';
                        }
                    }, this.config.fadeTimeout);
                    
                    // Habilitar scroll del body
                    document.body.classList.remove('no-scroll');
                }

                // Dispatch evento personalizado
                this.dispatchEvent('checkin:loadingEnd');

            } catch (error) {
                console.error('Error al ocultar loading:', error);
            }
        }

        /**
         * Dispara un evento personalizado
         * @param {string} eventName - Nombre del evento
         * @param {Object} detail - Detalles del evento
         */
        dispatchEvent(eventName, detail = {}) {
            const event = new CustomEvent(eventName, { 
                detail: { 
                    manager: this, 
                    ...detail 
                } 
            });
            document.dispatchEvent(event);
        }

        /**
         * Verifica si el loading está activo
         * @returns {boolean}
         */
        isLoading() {
            return this.loadingOverlay && this.loadingOverlay.classList.contains('show');
        }

        /**
         * Destruye la instancia y limpia eventos
         */
        destroy() {
            this.hideLoading();
            
            if (this.form) {
                this.form.removeEventListener('submit', this.handleFormSubmit);
            }
            
            window.removeEventListener('pageshow', this.hideLoading);
            window.removeEventListener('focus', this.hideLoading);
            window.removeEventListener('beforeunload', this.hideLoading);
        }
    }

    /**
     * API Pública
     */
    window.CheckinLoadingManager = CheckinLoadingManager;

    /**
     * Auto-inicialización para compatibilidad con código existente
     */
    let defaultManager = null;

    // Funciones globales para compatibilidad
    window.showLoading = function() {
        if (defaultManager) {
            defaultManager.showLoading();
        }
    };

    window.hideLoading = function() {
        if (defaultManager) {
            defaultManager.hideLoading();
        }
    };

    // Inicialización automática cuando el DOM esté listo
    function initializeDefaultManager() {
        defaultManager = new CheckinLoadingManager();
        
        // Exponer la instancia por defecto para acceso directo
        window.checkinLoading = defaultManager;
    }

    // Ejecutar inicialización
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeDefaultManager);
    } else {
        initializeDefaultManager();
    }

})();
