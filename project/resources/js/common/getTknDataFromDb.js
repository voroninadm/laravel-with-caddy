import {showNotification} from "@/common/notifications.js";

let debounceTimeout;

export function getTknDataFromDb(newNumber, oldNumber, callback, delay = 2500) {
    if (newNumber !== oldNumber) {
        if (debounceTimeout) {
            clearTimeout(debounceTimeout);
        }
        debounceTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`/api/get-orders/${newNumber}`, {
                    method: 'GET',
                });

                const data = await response.json();
                if (data.json_ok) {
                    showNotification('success', 'Данные успешно получены из db');
                    callback(data);
                } else {
                    showNotification('warning', 'Карта не найдена или содержит ошибку в db');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Карта не найдена, содержит ошибку в db, либо просто ошибка связи');
            }
        }, delay); //debounce
    }
}
