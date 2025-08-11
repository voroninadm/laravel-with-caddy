import {MAX_HOURS_FOR_ELOG_CHANGES} from "@/common/constants.js";

export function useAuth(props) {

    /**
     * Проверяем роль - является ли менеджером
     * @returns {boolean}
     */
    const isManagerCanSee = () => {
        return !props.auth.role.includes('manager');
    };


    /**
     * Предоставление доступа для изменения задач на основе прав доступа
     * @param taskWorkDate
     * @param taskType
     * @returns {boolean}
     */
    const isUserCanEditDelete = (taskWorkDate, taskType) => {
        if (props.auth.permissions.includes(`medium_${taskType}_permission`)) {
            const currentDateTime = new Date();
            const taskDateTime = new Date(taskWorkDate);

            const timeDifference = currentDateTime - taskDateTime;
            const hoursDifference = timeDifference / (1000 * 60 * 60);
            return hoursDifference <= MAX_HOURS_FOR_ELOG_CHANGES;
        } else if (props.auth.permissions.includes(`full_${taskType}_permission`)) {
            return true
        }
    }

    /**
     * Проверяем права юзера на доступ к изменениям задачи
     * @param taskType
     * @returns {boolean}
     */
    const isUserCanChangeTask = (taskType) => {
        return props.auth.permissions.includes(`medium_${taskType}_permission`) || props.auth.permissions.includes(`full_${taskType}_permission`)
    };

    return {
        isManagerCanSee, isUserCanEditDelete, isUserCanChangeTask
    };
}
