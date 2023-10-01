import SidebarItem from "./SidebarItem";
import SidebarItemHeader from "./SidebarItemHeader";

const Sidebar = ({appName, username, elements}) => {
    return (
        <aside className="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/" className="brand-link">
                <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                     className="brand-image img-circle elevation-3"
                     style={{opacity: .8}}/>
                <span className="brand-text font-weight-light">{appName}</span>
            </a>
            <div className="sidebar">
                <div className="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div className="image">
                        <img src="/dist/img/user2-160x160.jpg" className="img-circle elevation-2" alt="User Image"/>
                    </div>
                    <div className="info">
                        <a href="#" className="d-block">{username}</a>
                    </div>
                </div>
                <nav className="mt-2">
                    <ul className="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        {elements?.map((item, index) =>
                            item.isHeader ? (
                                <SidebarItemHeader key={index} {...item} />
                            ) : (
                                <SidebarItem key={index} {...item} />
                            )
                        )}
                    </ul>
                </nav>

            </div>

        </aside>
    );
}

export default Sidebar;
