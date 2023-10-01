const Badge = ({children, color, className}) => {

    return (
        <span className={`${className || ''} badge ${color ? 'badge-' + color : ''}`}>{children}</span>
    );
}

export default Badge
