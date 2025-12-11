function getInitials(fullName) {
    if (!fullName || typeof fullName !== 'string') {
        return ''; // Handle empty or non-string input
    }

    const nameParts = fullName.split(' ');
    let initials = '';

    for (let i = 0; i < nameParts.length; i++) {
        const part = nameParts[ i ];
        if (part.length > 0) {
            initials += part[ 0 ].toUpperCase();
        }
    }
    return initials;
}

export {
    getInitials
}