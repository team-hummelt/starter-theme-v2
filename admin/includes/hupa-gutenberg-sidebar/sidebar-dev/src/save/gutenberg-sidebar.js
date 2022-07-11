const { registerPlugin } = wp.plugins;
import Hupa_Custom_Sidebar from './export-gutenberg-sidebar';
registerPlugin( 'hupa-sidebar-options', {
    render() {
        return(<Hupa_Custom_Sidebar />);
    }
} );