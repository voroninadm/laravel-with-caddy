import axios from "axios";
import {showNotification} from "@/common/notifications.js";

export async function getEncostIdlesData (form) {
    const currentDate = new Date();
    const dateOfTaskFinish = new Date(form.work_finish);

    if (dateOfTaskFinish < currentDate)
        try {
            const response = await axios.get('/api/encost/get-idles', {
                params: {
                    machine_id: form.machine_id,
                    work_start: form.work_start,
                    work_finish: form.work_finish
                }
            });

            if (response.data) {
                showNotification('success', 'Данные из Энкост успешно получены и обработаны!');
                return response.data;
            } else {
                return showNotification('warning', 'Данные Энкоста не найдены или произошла ошибка');
            }
        } catch (error) {
            return showNotification('error', `Ошибка получения данных из Энкост.<br> Проверьте, правильно ли выбрана машина и заполнены ли поля "Начало работ" и "Окончание работ"`);
        }

    else {
       return showNotification('error', `Невозможно получить простои раньше указанного времени окончания работ`);
    }
}
