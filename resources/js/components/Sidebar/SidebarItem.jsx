import {Link} from "react-router-dom";
import Badge from "@/components/Badge/Badge";

const SidebarItem = (
    {
        title = '',
        icon = '',
        isActive = false,
        className = '',
        child = {isOpen: false},
        url, badge
    }
) => {
    const classNames = `nav-item ${child?.isOpen ? 'menu-open' : ''} ${className}`;
    const linkClasses = `nav-link ${isActive ? 'active' : ''}`;

    return (
        <li className={classNames}>
            <Link to={url || ''} className={linkClasses}>
                <i className={'nav-icon ' + icon}></i>
                <p>
                    {title}
                    {child?.elements && <i className={'right fas fa-angle-left'}/>}
                    {badge && <Badge className={'right'} {...badge}/>}
                </p>
            </Link>
            {child?.elements && (
                <ul className="nav nav-treeview">
                    {child?.elements.map((item, index) => (<SidebarItem key={index} {...item}/>))}
                </ul>
            )}
        </li>
    );
}

export default SidebarItem;
