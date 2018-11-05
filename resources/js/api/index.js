import axios from 'axios';


/** 分类 */
// Metas->Index
export const MetasGetAll = params => axios.get(`metas`, params);
export const CreateMetas = params => axios.post(`metas`, params);
export const DestroyMetas = params => axios.get(`/metas/destroy/${params.id}`);
export const RestoreMetas = params => axios.get(`/metas/restore/${params.id}`);
export const DeleteMetas = params => axios.get(`/metas/delete/${params.id}`);
export const UpdateMetas = params => axios.post(`/metas/update/${params.id}`, params.update);
/**标签 */
export const TagsGetAll = params => axios.get(`tags`, params);
/** 文章 */
export const ArticleGetAll = params => axios.get(`article`, params);
export const ArticleCreate = params => axios.post(`article`, params);
export const ArticleDestroy = params => axios.get(`/article/destroy/${params.id}`);
export const ArticleRestore = params => axios.get(`/article/restore/${params.id}`);
export const ArticleDelete = params => axios.get(`/article/delete/${params.id}`);
export const ArticleUpdate = params => axios.post(`/article/update/${params.id}`, params.update);
/** 友情链接 */
export const LinksAll = params => axios.get(`links`, params);
export const LinksCreate = params => axios.post(`links`, params);
export const LinksDelete = params => axios.get(`/links/delete/${params.id}`);
export const LinksUpdate = params => axios.post(`/links/update/${params.id}`, params.update);
export const LinksFile = params => axios.post(`/links/upload`, params);
/** 评论 */
export const CommentAll = params => axios.get(`comment`, params);
export const CommentCreate = params => axios.post(`comment`, params);
export const CommentUpdate = params => axios.post(`/comment/update/${params.id}`, params.update);
/** */
export const BlogMetas = params => axios.get(`blog/metas`, params);
