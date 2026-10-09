CREATE TABLE [dbo].[MasterSkillTree](
    [Name] [varchar](10) NOT NULL,
    [MasterLevel] [int] NOT NULL DEFAULT ((0)),
 CONSTRAINT [PK_MasterSkillTree] PRIMARY KEY CLUSTERED
(
    [Name] ASC
)
)