import { postAjax } from '../utils/ajax.js';

export default class UserTeamAssign {
  constructor() {
    this.endpointKey = 'project-desk-ajax-team-assignment';
    this.availableSelectors = document.querySelectorAll('.available-users');
    this.assignedSelectors = document.querySelectorAll('.assigned-users');
    this.init();
  }

init() {
  document.querySelectorAll('.user-assignment').forEach(wrapper => {
    const assignBtn = wrapper.querySelector('.assign');
    const unassignBtn = wrapper.querySelector('.unassign');
    const available = wrapper.querySelector('.available-users');
    const assigned = wrapper.querySelector('.assigned-users');
    const teamUid = wrapper.dataset.teamUid;

    if (assignBtn && unassignBtn && available && assigned) {
      assignBtn.addEventListener('click', () => this.moveSelected(available, assigned, 'add', teamUid));
      unassignBtn.addEventListener('click', () => this.moveSelected(assigned, available, 'delete', teamUid));

      available.addEventListener('dblclick', event => {
        if (event.target.tagName === 'OPTION') {
          this.moveSelected(available, assigned, 'add', teamUid, event.target);
        }
      });

      assigned.addEventListener('dblclick', event => {
        if (event.target.tagName === 'OPTION') {
          this.moveSelected(assigned, available, 'delete', teamUid, event.target);
        }
      });
    }
  });
}

  async moveSelected(fromSelect, toSelect, action, teamUid, specificOption = null) {
    const options = specificOption ? [specificOption] : Array.from(fromSelect.selectedOptions);

    for (const option of options) {
      const success = await this.sendChange(action, teamUid, option.value);
      if (success) {
        fromSelect.removeChild(option);
        toSelect.appendChild(option);

        if (action === 'delete') {
          this.ensureOptionInAvailableLists(option);
        }

        this.updateAllAvailableUsers();
      }
    }
  }

  ensureOptionInAvailableLists(option) {
    this.availableSelectors.forEach(select => {
      const exists = Array.from(select.options).some(opt => opt.value === option.value);
      if (!exists) {
        const clone = option.cloneNode(true);
        select.appendChild(clone);
      }
    });
  }

  updateAllAvailableUsers() {
    const assignedUserIds = new Set();
    this.assignedSelectors.forEach(select => {
      Array.from(select.options).forEach(opt => assignedUserIds.add(opt.value));
    });

    this.availableSelectors.forEach(select => {
      Array.from(select.options).forEach(option => {
        option.hidden = assignedUserIds.has(option.value);
      });
    });
  }

  async sendChange(action, teamUid, userId) {
    const payload = { action, teamUid, userId };
    const result = await postAjax(this.endpointKey, payload);
    return !!(result && result.success);
  }
}

new UserTeamAssign();
