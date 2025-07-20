import { postAjax } from '../utils/ajax.js';

export default class TeamTabSwitcher {
  constructor() {
    this.tabSelector = '.team-nav-link';
    this.containerSelector = '.user-assignment';
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
    // Aktive Klasse anpassen
    document.querySelectorAll(this.tabSelector).forEach(tab => {
      tab.classList.toggle('active', tab.dataset.teamKey === teamKey);
    });

    // Container ein-/ausblenden
    document.querySelectorAll(this.containerSelector).forEach(container => {
      container.style.display = container.dataset.teamUid === teamKey ? 'grid' : 'none';
    });
  }
}

new TeamTabSwitcher();
