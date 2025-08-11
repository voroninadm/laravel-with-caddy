export function useTimeUpdate() {
    const handleTimeUpdate = (form, field, value ) => {
        if (!value) return;

        const date = new Date(value);
        date.setMinutes(0);

        form[field] = date;
    };

    return { handleTimeUpdate };
}
