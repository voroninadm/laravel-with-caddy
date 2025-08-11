import moment from "moment/dist/moment";
import ru from "moment/dist/locale/ru";
import {MAX_DAYS_FOR_DATEPICKER, MAX_DAYS_TO_EXCEL_EXPORT} from "@/common/constants.js";
moment.updateLocale("ru", ru);


export const currentDate = moment();

/** Результат: 26 февраля 2025 г.*/
export const showLongCurrentDate = () => {
    return moment(new Date()).format('LL');
}

/** Результат: 25 февраля 08:00 */
export const normalizeDashboardDates = (date) => {
    return moment(date).format("D MMMM HH:mm");
};

export const daysFromCreate = (date) => {
    return moment(date).fromNow();
};

export const showPhpCurrentDate = () => {
    return currentDate.format('YYYY-MM-DD');
}
export const normalizeStringDate = (date) => {
    return moment(date, "DD.MM.YYYY").format("D MMM YYYY");
};

/**  26 фев 2025 или 26 февраля 2025 г. */
export const normalizeDate = (date, long = false) => {
    return !long ? moment(date).format("D MMM YYYY") : moment(date).format("D MMMM YYYY");
};

/**  7 окт. 2024 */
export const normalizeDatetime = (date) => {
    return moment(date).format("D MMM HH:mm");
};

/** Функция для создания ссылки (HR-тестированиие) в главном меню */
export const showCurrentYearMonth = () => {
   return currentDate.format('YYYY-MM');
}

/** для vue-datepicker при создании-изменении задач. */
export const calculateMinDatepickerDate = () => {
    const day = new Date();
    day.setDate(day.getDate() - MAX_DAYS_FOR_DATEPICKER);
    return day;
}

/** Дата и время - для подставления в новое создаваемое распоряжение */
export const dateForJsonOrder = () => {
    let currentDate = moment();
    return moment(currentDate).format("DD.MM.YYTH:mm:ss");
}

/**  Проверка временного интервала для выгрузки данных по всем работам */
export function checkMonthsToExcelExport (date_start, date_finish) {
    let start = moment(date_start);
    let finish = moment(date_finish);
    let daysDiff = finish.diff(start, "days");
    return +daysDiff < +MAX_DAYS_TO_EXCEL_EXPORT;
}
