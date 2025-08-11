import {showNotification} from "@/common/notifications.js";
import axios from "axios";

/**
 * Возврат на предыдущую страницу
 */
export const goBack = () => {
    window.history.back();
}

/**
 *  Подсчет фактического времени выполнения задачи
 * @param work_start date
 * @param work_finish date
 * @returns {number || null}
 */
export function calculateWorkFacts(work_start, work_finish) {
    let workFact = null;

    if (work_start && work_finish) {
        const start = new Date(work_start);
        const finish = new Date(work_finish);
        const diff = finish - start;
        const hours = diff / (1000 * 60 * 60);
        workFact = +hours.toFixed(2)
    }
    return workFact;
}

/**
 * Задаем номер смены по умолчанию исходя из текущего часа
 * @param startHour
 * @param finishHour
 * @returns {number}
 */
export function setWorkShiftBasedOnTime(startHour, finishHour) {
    const currentHour = new Date().getHours();
    return currentHour >= startHour && currentHour < finishHour ? 1 : 2;
}

/**
 * функция калькуляции выработки в м2 по формуле
 */
export function calculateWorkoutCountM2(width, workout_length) {
    let count = null;
    if (width && workout_length) {
        const calc = (width * workout_length / 1000);
        count = calc.toFixed(2);
    }
    return count;
}

/**
 * функция cуммирования отходов
 */
export function calculateWaste(...values) {
    return values.reduce((total, current) => {
        const num = parseFloat(current) || null;
        return total + num;
    }, 0);
}



// разница тиража

export async function calcCirculation(formFields, circulation_fields) {
    try {
        const response = await axios.post('/backend/get-circulations', {
            tkn: formFields.tkn,
            type: formFields.type,
        });
        if (response.data) {
            showNotification('success', 'Запрос на расчет разницы тиража успешен!');
            if (response.data.tasksCount > 0) {
                showNotification('info', `${response.data.taskType}. По карте ${formFields.tkn} уже создано задач: ` + response.data.tasksCount + `. <br> Общая сумма выработки: ` + response.data.tasksCirculation + ' кг');
            } else {
                showNotification('info', `${response.data.taskType}. Это первая задача по карте ${formFields.tkn}!`);
            }
            const totalCirculationFields = circulation_fields.reduce((acc, value) => acc + value, 0);
            let circulation;
            const calc = (+formFields.planOfCirculation - (+totalCirculationFields + +response.data.tasksCirculation));
            circulation = calc.toFixed(2);
            return circulation;
        } else {
            showNotification('warning', 'Ошибка расчета');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Ошибка расчета. Проверьте, правильно ли выбрана машина и номер карты');
    }
}

// выдаем svg при truе/false позиции из Elog task
export const isOkSvgShow = (task_position) => {
    if (task_position) {
        return `<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 m-auto" viewBox="0 0 1024 1024">
  <path fill="#8BC34A" d="m512 64 100.3 76.8 123.7-17L783 241l117.2 47-17.1 123.7L960 512l-76.8 100.3 17 123.7L783 783l-47 117.2-123.7-17.1L512 960l-100.3-76.8-123.7 17L241 783l-117.2-47 17.1-123.7L64 512l76.8-100.3-17-123.7L241 241l47-117.2 123.7 17.1z"/>
  <path fill="#CCFF90" d="M738.1 311.5 448 601.6 328.5 482.1 268.8 542 448 721l349.9-349.9z"/>
</svg>
`;
    } else {
        return task_position === null ? '' :
            `<svg class="w-8 h-8 m-auto" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" viewBox="0 0 512 512">
  <circle cx="256" cy="256" r="245.8" fill="#ff757c"/>
  <path fill="#f2f2f2" d="M395.6 164 348 116.4l-92 92-92-92-47.6 47.6 92 92-92 92 47.6 47.6 92-92 92 92 47.6-47.6-92-92z"/>
  <g fill="#4d4d4d">
    <path d="M256 512c-68.4 0-132.7-26.6-181-75S0 324.4 0 256 26.6 123.3 75 75 187.6 0 256 0s132.7 26.6 181 75 75 112.6 75 181-26.6 132.7-75 181-112.6 75-181 75zm0-491.6C126 20.4 20.4 126.1 20.4 256S126.1 491.6 256 491.6 491.6 385.9 491.6 256 385.9 20.4 256 20.4z"/>
    <path d="M348 405.8c-2.6 0-5.3-1-7.3-3L256 318l-84.7 84.8c-4 4-10.5 4-14.5 0l-47.6-47.6c-4-4-4-10.5 0-14.5L194 256l-84.8-84.7c-4-4-4-10.5 0-14.5l47.6-47.6c4-4 10.5-4 14.5 0L256 194l84.7-84.8c4-4 10.5-4 14.5 0l47.6 47.6c4 4 4 10.5 0 14.5L318 256l84.8 84.7c4 4 4 10.5 0 14.5l-47.6 47.6c-2 2-4.6 3-7.2 3zm-92-112.4c2.6 0 5.2 1 7.2 3L348 381l33-33-84.7-84.8c-4-4-4-10.4 0-14.4L381 164l-33-33-84.8 84.7c-4 4-10.4 4-14.4 0L164 131l-33 33 84.7 84.8c4 4 4 10.4 0 14.4L131 348l33 33 84.8-84.7c2-2 4.6-3 7.2-3zm198.4 45.2a10.2 10.2 0 0 1-9.6-13.7 203 203 0 0 0 11.5-84.4 10.2 10.2 0 0 1 20.4-1.6c2.4 31.4-2 63.5-12.7 93a10.2 10.2 0 0 1-9.6 6.7zm5.2-128.5c-4.5 0-8.6-3-9.9-7.5-.8-3-1.6-5.9-2.6-8.7a10.2 10.2 0 1 1 19.4-6.3l3 9.6a10.2 10.2 0 0 1-10 13z"/>
  </g>
</svg>`
    }
}

// считаем среднее по значению
export const averaging = (tasks, taskColumn) => {
    // Фильтруем задачи где не заполнены значения taskColumn
    const validTasks = tasks.filter(task => task[taskColumn] !== null && task[taskColumn] !== 0 && task[taskColumn] !== '');
    if (validTasks.length === 0) {
        return '';
    }
    const total = validTasks.reduce((sum, task) => sum + parseFloat(task[taskColumn] || 0), 0);
    return 'μ:' + (total / validTasks.length).toFixed(2);
};

export const summarize = (tasks, taskColumn) => {
    // Фильтруем задачи где не заполнены значения taskColumn
    const validTasks = tasks.filter(task => task[taskColumn] !== null && task[taskColumn] !== 0 && task[taskColumn] !== '');
    if (validTasks.length === 0) {
        return '';
    }
    const total = validTasks.reduce((sum, task) => sum + parseFloat(task[taskColumn] || 0), 0);
    return 'Σ:' + total.toFixed(2);
};

export const uniqueCardCount = (tasks, taskColumn) => {
    const taskColumnValues = tasks.map(task => task[taskColumn]);
    const uniqueValues = new Set();

    taskColumnValues.forEach(value => {
        if (value !== '' && value !== null && value !== '0' && value !== 0) {
            uniqueValues.add(value);
        }
    });

    return uniqueValues.size;
}
