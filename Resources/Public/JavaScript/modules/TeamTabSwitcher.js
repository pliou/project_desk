import { postAjax } from '../utils/ajax.js';

export default class TeamTabSwitcher {
  constructor() {
    this.tabSelector = '.team-nav-link';
    this.activeInput = document.getElementById('active_team');
    this.formSelector = '[data-access-form]'; 
    this.init();
  }

  init() {
    document.querySelectorAll(this.tabSelector).forEach(tab => {
      tab.addEventListener('click', event => {
        event.preventDefault();
        this.switchTab(tab.dataset.teamKey);
      });
    });
  }

  switchTab(teamKey) {
    document.querySelectorAll(this.tabSelector).forEach(tab => {
      tab.classList.toggle('active', tab.dataset.teamKey === teamKey);
    });

    if (this.activeInput) {
      this.activeInput.value = teamKey;
    }

    this.loadAccessConfig(teamKey);
  }

  async loadAccessConfig(teamKey) {
    try {
      const response = await postAjax('project-desk-ajax-get-access-config', { team: teamKey });

      if (!response.success || !response.data) {
        console.warn('No config returned');
        return;
      }

      this.updateFormFields(response.data);
    } catch (e) {
      console.error('Failed to load config', e);
    }
  }

  updateFormFields(config) {
    // Wenn kein Config-Objekt vorhanden ist oder es leer ist, alles deaktivieren
    if (!config || Object.keys(config).length === 0) {
      // Alle Checkboxen, die Teil der Konfiguration sein könnten, deaktivieren
      document.querySelectorAll('input[type="checkbox"][name^="config["]').forEach(cb => {
        cb.checked = false;
      });
      return;
    }

    Object.entries(config).forEach(([key, value]) => {
      // Mehrfach-Checkboxen: z.B. task_permissions, phase_permissions
      if (Array.isArray(value)) {
        const checkboxes = document.querySelectorAll(`input[type="checkbox"][name^="config[${key}]"]`);
        checkboxes.forEach(cb => {
          cb.checked = value.includes(cb.value);
        });
        return;
      }

      // Einzelne Checkbox (boolean oder 0/1)
      const checkbox = document.querySelector(`input[type="checkbox"][name="config[${key}]"]`);
      if (checkbox) {
        checkbox.checked = !!value;
        return;
      }

      // Textfelder, Textareas, Selects
      const input = document.querySelector(`[name="config[${key}]"]`);
      if (input) {
        if (input.tagName === 'SELECT' || input.tagName === 'TEXTAREA' || input.type === 'text') {
          input.value = value;
        }
      }
    });
  }

}

new TeamTabSwitcher();
