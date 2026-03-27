class Todo {
  constructor() {
    this.tasks = JSON.parse(localStorage.getItem('todoTasks')) || [];
    this.term = "";

    this.listaUl = document.getElementById('lista_zdania');
    this.inputDodaj = document.getElementById('tresc_dodaj');
    this.inputData = document.getElementById('data_dodaj');
    this.formStopka = document.getElementById('stopka');
    this.inputSzukaj = document.getElementById('wyszukiwanie');

    this.init();
  }

  init() {
    this.formStopka.addEventListener('submit', (e) => {
      e.preventDefault();
      this.add(this.inputDodaj.value, this.inputData.value);
      this.inputDodaj.value = "";
      this.inputData.value = "";
    });

    this.inputSzukaj.addEventListener('input', (e) => {
      this.term = e.target.value;
      this.draw();
    });

    this.draw();
  }

  add(title, date) {
    if (title.length < 3) return;
    this.tasks.push({
      id: Date.now(),
      title: title,
      date: date
    });
    this.saveAndDraw();
  }

  remove(id) {
    this.tasks = this.tasks.filter(t => t.id !== id);
    this.saveAndDraw();
  }

  saveAndDraw() {
    localStorage.setItem('todoTasks', JSON.stringify(this.tasks));
    this.draw();
  }

  draw() {
    this.listaUl.innerHTML = "";

    let filtered = this.tasks;
    if (this.term.length >= 2) {
      filtered = this.tasks.filter(t =>
        t.title.toLowerCase().includes(this.term.toLowerCase())
      );
    }

    filtered.forEach((task) => {
      const li = document.createElement('li');
      const contentDiv = document.createElement('div');
      contentDiv.className = "zdanie-kontener";

      const span = document.createElement('span');
      span.className = "zdanie";
      let displayTitle = task.title;
      if (this.term.length >= 2) {
        const regex = new RegExp(`(${this.term})`, 'gi');
        displayTitle = task.title.replace(regex, '<mark>$1</mark>');
      }
      span.innerHTML = displayTitle;

      const dateSpan = document.createElement('small');
      dateSpan.innerText = task.date ? task.date.replace('T', ' ') : "Brak daty";

      span.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = "text";
        input.value = task.title;
        input.className = "edit-input";
        input.onblur = () => {
          if (input.value.length >= 3) {
            task.title = input.value;
            this.saveAndDraw();
          } else { this.draw(); }
        };
        input.onkeypress = (e) => { if (e.key === 'Enter') input.blur(); };
        contentDiv.replaceChild(input, span);
        input.focus();
      });

      dateSpan.addEventListener('click', () => {
        const dInput = document.createElement('input');
        dInput.type = "datetime-local";
        dInput.value = task.date;
        dInput.onblur = () => {
          task.date = dInput.value;
          this.saveAndDraw();
        };
        contentDiv.replaceChild(dInput, dateSpan);
        dInput.focus();
      });

      const btn = document.createElement('button');
      btn.className = "delete-btn";
      btn.innerText = "Usuń";
      btn.onclick = () => this.remove(task.id);

      contentDiv.appendChild(span);
      contentDiv.appendChild(dateSpan);
      li.appendChild(contentDiv);
      li.appendChild(btn);
      this.listaUl.appendChild(li);
    });
  }
}

const todo = new Todo();
