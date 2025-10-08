/**
 * Checkin Loading Manager
 * Sistema de gestión de indicador de carga para formularios de check-in
 * Author: Departamento Desarrollo MABEL
 * Version: 2.0
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
                // Nueva configuración para múltiples botones
                enableMultipleButtons: true,
                buttonLoadingText: 'Cargando...',
                buttonLoadingClass: 'btn-loading',
                buttonSpinnerClass: 'btn-spinner',
                // Selectores para diferentes tipos de botones
                allButtonsSelector: 'button',
                resetButtonSelector: 'button[type="reset"]',
                submitButtonsSelector: 'button[type="submit"], .btn-submit',
                actionButtonsSelector: '.btn-action, .btn-primary, .btn-secondary',
                // Botones excluidos del sistema de spinner
                excludedButtons: [
                    '#prev-page',      // Botón Anterior del visualizador PDF
                    '#next-page',      // Botón Siguiente del visualizador PDF
                    '#zoom-in',        // Botón Zoom In del visualizador PDF
                    '#zoom-out',       // Botón Zoom Out del visualizador PDF
                    '.btn-nav',        // Clase genérica para botones de navegación
                    '.no-spinner',     // Clase genérica para excluir spinners
                    'button[type="reset"]' // Botones de reset (limpiar formulario)
                ],
                ...options
            };
            
            this.form = null;
            this.submitButton = null;
            this.loadingOverlay = null;
            this.pasaporteInput = null;
            
            // Nuevas propiedades para múltiples botones
            this.buttons = new Map(); // Para almacenar estado de cada botón
            this.activeButtons = [];
            
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
            // Configurar eventos básicos de formulario si existen
            if (this.form && this.submitButton) {
                this.form.addEventListener('submit', (event) => this.handleFormSubmit(event));
            }
            
            // Configurar múltiples botones si está habilitado
            if (this.config.enableMultipleButtons) {
                this.setupMultipleButtons();
            }
            
            // Solo configurar eventos de loading si hay overlay disponible
            if (this.loadingOverlay) {
                // Eventos para ocultar loading
                window.addEventListener('pageshow', () => this.hideAllLoading());
                window.addEventListener('focus', () => this.hideAllLoading());
                
                // Evento para limpiar loading en caso de error
                window.addEventListener('beforeunload', () => this.hideAllLoading());
            }
        }

        /**
         * Configura el manejo de múltiples botones
         */
        setupMultipleButtons() {
            // Buscar todos los botones de submit
            const submitButtons = document.querySelectorAll(this.config.submitButtonsSelector);
            submitButtons.forEach(button => {
                if (!this.isExcludedButton(button, this.config.excludedButtons)) {
                    this.setupButton(button, 'submit');
                }
            });

            // Buscar botones de acción
            const actionButtons = document.querySelectorAll(this.config.actionButtonsSelector);
            actionButtons.forEach(button => {
                if (!this.isExcludedButton(button, this.config.excludedButtons)) {
                    this.setupButton(button, 'action');
                }
            });

            // Manejo especial para botones reset
            const resetButtons = document.querySelectorAll('button[type="reset"]');
            resetButtons.forEach(button => this.setupResetButton(button));

            // Configurar botones de un solo clic
            const singleClickButtons = document.querySelectorAll('.btn-single-click');
            singleClickButtons.forEach(button => this.setupSingleClickButton(button));

            // Buscar botones con atributo data-loading (estos siempre se incluyen si están marcados explícitamente)
            const customButtons = document.querySelectorAll('[data-loading]');
            customButtons.forEach(button => {
                if (!this.isExcludedButton(button, this.config.excludedButtons)) {
                    this.setupButton(button, 'custom');
                }
            });
        }

        /**
         * Verifica si un botón está en la lista de excluidos
         * @param {HTMLElement} button - El botón a verificar
         * @param {Array} excludedSelectors - Lista de selectores excluidos
         * @returns {boolean}
         */
        isExcludedButton(button, excludedSelectors) {
            return excludedSelectors.some(selector => {
                if (selector.startsWith('#')) {
                    // Comparar por ID
                    return button.id === selector.substring(1);
                } else if (selector.startsWith('.')) {
                    // Comparar por clase
                    return button.classList.contains(selector.substring(1));
                } else {
                    // Comparar por selector CSS
                    return button.matches(selector);
                }
            });
        }

        /**
         * Configura un botón individual
         * @param {HTMLElement} button - El elemento botón
         * @param {string} type - Tipo de botón (submit, action, custom)
         */
        setupButton(button, type = 'action') {
            if (!button || this.buttons.has(button)) return;

            const buttonConfig = {
                element: button,
                type: type,
                originalText: button.textContent || button.innerHTML,
                originalDisabled: button.disabled,
                isLoading: false,
                form: button.closest('form')
            };

            this.buttons.set(button, buttonConfig);

            // Agregar event listeners según el tipo
            if (type === 'submit' && buttonConfig.form) {
                // Los botones de submit se manejan en el evento del formulario
                return;
            } else {
                // Otros botones se manejan individualmente
                button.addEventListener('click', (event) => this.handleButtonClick(event, buttonConfig));
            }
        }

        /**
         * Configura un botón reset con manejo especial
         * @param {HTMLElement} button - El elemento botón reset
         */
        setupResetButton(button) {
            if (!button) return;

            // Solo agregar efecto visual sin interferir con la funcionalidad
            button.addEventListener('click', (event) => {
                const duration = parseInt(button.getAttribute('data-loading-duration')) || 500;
                
                // Agregar clase de animación temporal
                button.classList.add('btn-reset-animation');
                
                // Remover la clase después de la duración especificada
                setTimeout(() => {
                    button.classList.remove('btn-reset-animation');
                }, duration);
                
                // No prevenir el comportamiento por defecto del reset
                // El formulario se limpiará normalmente
            });
        }

        /**
         * Configura un botón de un solo clic con protección contra doble clic
         * @param {HTMLElement} button - El elemento botón
         */
        setupSingleClickButton(button) {
            if (!button) return;

            let isClicked = false;
            
            // Buscar el enlace padre si existe
            const parentLink = button.closest('a');

            const handleClick = (event) => {
                // Prevenir múltiples clics
                if (isClicked) {
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                // Marcar como clickeado
                isClicked = true;
                button.classList.add('btn-clicked');
                button.disabled = true;

                // Si hay un enlace padre, aplicar cursor prohibido y permitir navegación con delay
                if (parentLink && parentLink.href) {
                    event.preventDefault();
                    
                    // Aplicar cursor prohibido al enlace
                    parentLink.style.cursor = 'not-allowed';
                    parentLink.style.pointerEvents = 'auto'; // Permitir hover para mostrar cursor
                    
                    // Pequeño delay para mostrar el feedback visual
                    setTimeout(() => {
                        window.location.href = parentLink.href;
                    }, 200);
                }

                // Dispatch evento personalizado
                this.dispatchEvent('checkin:singleClickButton', {
                    button: button,
                    parentLink: parentLink
                });
            };

            // Solo agregar el evento al enlace padre si existe, si no al botón
            if (parentLink) {
                parentLink.addEventListener('click', handleClick);
            } else {
                button.addEventListener('click', handleClick);
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
            
            // Encontrar el botón de submit que disparó el evento
            const activeSubmitButton = event.submitter || this.submitButton;
            
            // Mostrar indicador de carga
            this.showLoading();
            
            // También mostrar loading en el botón específico si está configurado
            if (activeSubmitButton && this.buttons.has(activeSubmitButton)) {
                this.showButtonLoading(activeSubmitButton);
            }
        }

        /**
         * Maneja el click en botones individuales
         * @param {Event} event - Evento de click
         * @param {Object} buttonConfig - Configuración del botón
         */
        handleButtonClick(event, buttonConfig) {
            const button = buttonConfig.element;
            const loadingType = button.getAttribute('data-loading') || 'spinner';
            const loadingDuration = parseInt(button.getAttribute('data-loading-duration')) || 0;
            
            // Mostrar loading en el botón
            this.showButtonLoading(button);
            
            // Si tiene duración especificada, ocultar automáticamente
            if (loadingDuration > 0) {
                setTimeout(() => {
                    this.hideButtonLoading(button);
                }, loadingDuration);
            }
            
            // Si es un enlace o tiene href, permitir navegación
            if (button.tagName === 'A' || button.getAttribute('href')) {
                // El loading se ocultará automáticamente al cambiar de página
            }
        }

        /**
         * Muestra loading en un botón específico
         * @param {HTMLElement} button - El botón
         */
        showButtonLoading(button) {
            const config = this.buttons.get(button);
            if (!config || config.isLoading) return;

            try {
                config.isLoading = true;
                
                // Deshabilitar el botón
                button.disabled = true;
                
                // Agregar clases de loading
                button.classList.add(this.config.buttonLoadingClass);
                button.classList.add(this.config.buttonSpinnerClass);
                
                // Cambiar texto si es necesario
                const loadingText = button.getAttribute('data-loading-text') || this.config.buttonLoadingText;
                if (loadingText && loadingText !== config.originalText) {
                    button.textContent = loadingText;
                }
                
                // Agregar a la lista de botones activos
                if (!this.activeButtons.includes(button)) {
                    this.activeButtons.push(button);
                }
                
                // Dispatch evento personalizado
                this.dispatchEvent('checkin:buttonLoadingStart', { button, config });
                
            } catch (error) {
                console.error('Error al mostrar loading en botón:', error);
                this.hideButtonLoading(button);
            }
        }

        /**
         * Oculta loading en un botón específico
         * @param {HTMLElement} button - El botón
         */
        hideButtonLoading(button) {
            const config = this.buttons.get(button);
            if (!config || !config.isLoading) return;

            try {
                config.isLoading = false;
                
                // Restaurar estado original
                button.disabled = config.originalDisabled;
                
                // Remover clases de loading
                button.classList.remove(this.config.buttonLoadingClass);
                button.classList.remove(this.config.buttonSpinnerClass);
                
                // Restaurar texto original
                button.textContent = config.originalText;
                
                // Remover de la lista de botones activos
                const index = this.activeButtons.indexOf(button);
                if (index > -1) {
                    this.activeButtons.splice(index, 1);
                }
                
                // Dispatch evento personalizado
                this.dispatchEvent('checkin:buttonLoadingEnd', { button, config });
                
            } catch (error) {
                console.error('Error al ocultar loading en botón:', error);
            }
        }

        /**
         * Oculta loading en todos los botones activos
         */
        hideAllLoading() {
            // Ocultar overlay principal
            this.hideLoading();
            
            // Ocultar loading en todos los botones activos
            [...this.activeButtons].forEach(button => {
                this.hideButtonLoading(button);
            });
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
    window.showLoading = function(buttonSelector = null) {
        if (defaultManager) {
            if (buttonSelector) {
                const button = document.querySelector(buttonSelector);
                if (button) {
                    defaultManager.showButtonLoading(button);
                } else {
                    defaultManager.showLoading();
                }
            } else {
                defaultManager.showLoading();
            }
        }
    };

    window.hideLoading = function(buttonSelector = null) {
        if (defaultManager) {
            if (buttonSelector) {
                const button = document.querySelector(buttonSelector);
                if (button) {
                    defaultManager.hideButtonLoading(button);
                } else {
                    defaultManager.hideLoading();
                }
            } else {
                defaultManager.hideAllLoading();
            }
        }
    };

    // Nuevas funciones específicas para botones
    window.showButtonLoading = function(buttonSelector) {
        if (defaultManager) {
            const button = typeof buttonSelector === 'string' ? 
                         document.querySelector(buttonSelector) : buttonSelector;
            if (button) {
                defaultManager.showButtonLoading(button);
            }
        }
    };

    window.hideButtonLoading = function(buttonSelector) {
        if (defaultManager) {
            const button = typeof buttonSelector === 'string' ? 
                         document.querySelector(buttonSelector) : buttonSelector;
            if (button) {
                defaultManager.hideButtonLoading(button);
            }
        }
    };

    // Función para proteger botones contra doble clic
    window.protectFromDoubleClick = function(buttonSelector) {
        if (defaultManager) {
            const button = typeof buttonSelector === 'string' ? 
                         document.querySelector(buttonSelector) : buttonSelector;
            if (button) {
                button.classList.add('btn-single-click');
                defaultManager.setupSingleClickButton(button);
            }
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
