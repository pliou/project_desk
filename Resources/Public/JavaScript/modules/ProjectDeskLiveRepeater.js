import { postAjax } from '../utils/ajax.js';

export default class ProjectDeskLiveRepeater {
  translations = window.TYPO3.settings.ProjectDesk?.translations ?? {};

  t(key) {
    return this.translations[key] || key;
  }

  constructor(endpointKey) {
    this.endpointKey = endpointKey;
    document.addEventListener("DOMContentLoaded", () => {
      this.initRepeaters();
    });
  }

  initRepeaters() {
    document.querySelectorAll("[id$='-container']").forEach(container => {
      const name = container.id.replace("-container", "");
      // Kein "new-{name}-input" mehr, da kein separates Input-Feld
      const button = document.getElementById(`add-${name}-btn`);

      if (!button) return;

      // Add-Button fügt nur eine neue leere Eingabezeile hinzu (ohne Ajax)
      button.addEventListener("click", () => {
        this.clearGlobalError(container);

        // Leere Eingabezeile erstellen
        const wrapper = this.createInput(name, "");
        container.appendChild(wrapper);
      });

      // Entfernen: Klick auf Entfernen-Button
      container.addEventListener("click", e => {
        if (e.target.classList.contains("remove-member")) {
          const item = e.target.closest(".repeater-item");
          const inputEl = item?.querySelector("input");
          if (inputEl) {
            // Wenn Eingabewert leer: nur UI entfernen, kein Ajax
            if (inputEl.value.trim() === "") {
              item.remove();
            } else {
              // Ansonsten Ajax-Remove aufrufen
              this.sendChange("remove", name, inputEl.value).then(success => {
                if (success) {
                  item.nextElementSibling?.classList.contains("repeater-error-message") && item.nextElementSibling.remove();
                  item.remove();
                } else {
                  this.showGlobalError(container, this.t("remove_failed"));
                }
              });
            }
          }
        }
      });

      // Altwert bei Fokus speichern
      container.addEventListener("focusin", e => {
        if (e.target.tagName === "INPUT") {
          e.target.dataset.oldValue = e.target.value;
        }
      });

      // Änderungen mit Debounce und Ajax speichern
      container.addEventListener("input", this.debounce(e => {
        if (e.target.tagName === "INPUT") {
          const newValue = e.target.value.trim();
          const oldValue = e.target.dataset.oldValue?.trim() ?? "";

          this.clearGlobalError(container);
          this.clearError(e.target);

          if (!newValue || newValue === oldValue || this.isDuplicate(container, newValue, e.target)) {
            this.showError(e.target, this.t("team_unchanged"));
            return;
          }

          // NEUE LOGIK: Wenn kein alter Wert gesetzt => "add", sonst "change"
          if (oldValue === "") {
            this.sendChange("add", name, newValue).then(success => {
              if (success) {
                e.target.dataset.oldValue = newValue; // oldValue jetzt setzen
                this.clearError(e.target);
              } else {
                this.showGlobalError(container, this.t("save_failed"));
              }
            });
          } else {
            this.sendChange("change", name, newValue, oldValue).then(success => {
              if (success) {
                e.target.dataset.oldValue = newValue;
                this.clearError(e.target);
              } else {
                this.showGlobalError(container, this.t("update_failed"));
              }
            });
          }
        }
      }, 1000));
    });
  }

  createInput(name, value) {
    const wrapper = document.createElement("div");
    wrapper.className = "input-group mb-2 repeater-item";
    wrapper.innerHTML = `
      <input type="text" class="form-control" name="config[${name}][]" value="${this.escapeHtml(value)}" placeholder="${this.t("placeholder_team_name")}" />
      <button type="button" class="remove-member" aria-label="Remove">&times;</button>
    `;
    return wrapper;
  }

  escapeHtml(str) {
    return str.replaceAll("&", "&amp;")
              .replaceAll("<", "&lt;")
              .replaceAll(">", "&gt;")
              .replaceAll('"', "&quot;")
              .replaceAll("'", "&#039;");
  }

  debounce(callback, delay) {
    let timeout;
    return (...args) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => callback(...args), delay);
    };
  }

  isDuplicate(container, value, exclude = null) {
    const inputs = container.querySelectorAll("input");
    return Array.from(inputs).some(input => input !== exclude && input.value.trim().toLowerCase() === value.toLowerCase());
  }

  showError(inputEl, message) {
    inputEl.classList.add("is-invalid");

    const wrapper = inputEl.closest(".repeater-item") ?? inputEl.parentElement;

    const nextEl = wrapper.nextElementSibling;
    if (nextEl && nextEl.classList.contains("repeater-error-message")) {
      nextEl.remove();
    }

    const error = document.createElement("div");
    error.className = "repeater-error-message text-danger small mt-1";
    error.innerText = message;

    wrapper.after(error);
  }

  clearError(inputEl) {
    inputEl.classList.remove("is-invalid");

    const wrapper = inputEl.closest(".repeater-item") ?? inputEl.parentElement;
    const nextEl = wrapper.nextElementSibling;
    if (nextEl && nextEl.classList.contains("repeater-error-message")) {
      nextEl.remove();
    }
  }

  showGlobalError(container, message) {
    let errorBox = container.querySelector(".repeater-global-error");
    if (!errorBox) {
      errorBox = document.createElement("div");
      errorBox.className = "alert alert-danger repeater-global-error";
      container.prepend(errorBox);
    }
    errorBox.innerText = message;
  }

  clearGlobalError(container) {
    const errorBox = container.querySelector(".repeater-global-error");
    if (errorBox) errorBox.remove();
  }

  async sendChange(action, field, value, oldValue = null) {
    const payload = { action, field, value };
    if (action === 'change' && oldValue) {
      payload.oldValue = oldValue;
    }

    const result = await postAjax(this.endpointKey, payload);
    return !!(result && result.success);
  }
}

new ProjectDeskLiveRepeater('project-desk-ajax-repeater-update');
