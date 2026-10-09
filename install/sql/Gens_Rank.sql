CREATE TABLE [dbo].[Gens_Rank](
    [Name] [varchar](10) NOT NULL,
    [Family] [tinyint] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_Gens_Rank] PRIMARY KEY CLUSTERED
(
    [Name] ASC
)
)