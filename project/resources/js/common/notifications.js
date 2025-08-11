import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

// шаблон для горячих уведомлений
export const showNotification = (type, message, duration = 3000) => {
    $toast.open({
        message: message,
        type: type,
        duration: duration,
        position: 'bottom-right',
    });
};
