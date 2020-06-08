import ReaccionCMSUploadAdapter from "./ReaccionCMSUploadAdapter";

export default function ReaccionCMSUploadPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        let imageUploadRoute = Routing.generate('reaccion_cms_admin_media_image_upload');
        return new ReaccionCMSUploadAdapter(loader, imageUploadRoute);
    };
}
