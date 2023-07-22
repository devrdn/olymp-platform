import axios from "axios";

const API_URL = "/api/v1/solution";

const formTableRow = (task, index, len) => {
    return `<tr>
                <td>${len - index}</td>
                <td>${task.solution_status.name}</td>
            </tr>`
}

document.addEventListener('DOMContentLoaded', () => {

    // get user and task info
    const userData = document.querySelector('#user-data');
    const userId = userData.dataset.user;
    const taskId = userData.dataset.task;

    const table = document.querySelector('#solution_status tbody')


    const onSuccessResponse = (response) => {
        let html = "";
        const tasks = response.data.tasks;
        const len  = tasks.length;

        tasks.forEach((task, i) => {
            html += formTableRow(task, i, len)
        })

        table.innerHTML = html;
    }

    const onErrorResponse = (response) => {
        clearInterval(interval)
    }

    const apiUrl = `${API_URL}/${userId}/${taskId}`;
    const interval = setInterval(async () => {
        axios.get(apiUrl)
            .then(response => onSuccessResponse(response))
            .catch(response => onErrorResponse(response));
    }, 1000);
});

